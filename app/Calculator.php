<?php

/**
 * FakturoidCalc.
 *
 * @author Jan Javorek <honza@javorek.net>
 * @copyright Copyright (c) 2010 Jan Javorek
 */


/**
 * Calculates all the dirty numbers.
 *
 * @author Jan Javorek <honza@javorek.net>
 */
class Calculator
{
	protected $cfg = array();
	
	/**
	 * @var FakturoidModel
	 */
	protected $model;
	
	public function __construct($file)
	{
		$file = dirname(__FILE__) . '/../data/' . $file;
		if (!is_file($file)) {
			throw new Exception('Soubor s konfigurací neexistuje.');
		}
		$this->cfg = parse_ini_file($file, TRUE);
	}
	
	/**
	 * Lazy model provider.
	 * 
	 * @return FakturoidModel
	 */
	protected function getModel()
	{
		if (!$this->model) {
			if (empty($this->cfg['username']) || empty($this->cfg['api_key'])) {
				throw new Exception('V konfiguraci chybí uživatelské jméno nebo API klíč.');
			}
			$this->model = new FakturoidModel($this->cfg['username'], $this->cfg['api_key']);
		}
		return $this->model;
	}
	
	public function run()
	{
		$results = array();
		$results['cfg'] = $this->cfg;
		
		// basic results
		$isStudent = (bool)$this->cfg['student'];
		$results['income'] = ceil($this->getModel()->getIncome($this->cfg['year']));
		$results['expenses'] = ceil($results['income'] * ($this->cfg['expenses_percent'] / 100));
		$results['profit'] = ceil($results['income'] - $results['expenses']);
		
		// taxes
		$results['taxes'] = array();
		$results['taxes']['base'] = ceil(round($results['profit'], -2, PHP_ROUND_HALF_DOWN));
		$results['taxes']['tax'] = $results['taxes']['base'] * ($this->cfg['taxes']['percent'] / 100);
		
		$results['taxes']['reducedTax'] = $results['taxes']['tax'] - $this->cfg['taxes']['default_reduction'];
		if ($isStudent) {
			$results['taxes']['reducedTax'] -= $this->cfg['taxes']['student_reduction'];
		}
		
		$results['taxes']['finalTax'] = ($results['taxes']['reducedTax'] < 0)? 0 : $results['taxes']['reducedTax'];
		
		// health insurance
		$results['health_insurance'] = array();
		$percent = $this->cfg['health_insurance']['percent'];
		
		$minBaseMonthCount = ($isStudent)? 0 : 12;
		$base = min(max(0.5 * $results['profit'], $minBaseMonthCount * $this->cfg['health_insurance']['min_base']), $this->cfg['health_insurance']['max_base']);
		$results['health_insurance']['base'] = ($base < 0)? 0 : $base;
		$results['health_insurance']['insurance'] = $percent * 2 * $results['health_insurance']['base'];
		
		$results['health_insurance']['deposit'] = min(($percent * $results['profit']) / 12, $this->cfg['health_insurance']['max_deposit']);
		if ($isStudent) {
			$results['health_insurance']['deposit'] = max($results['health_insurance']['deposit'], $this->cfg['health_insurance']['min_deposit']);
		}
		$results['health_insurance']['deposit'] = round($results['health_insurance']['deposit'], 0, PHP_ROUND_HALF_UP);
		
		// social insurance
		$results['social_insurance'] = array();
		$percent = $this->cfg['social_insurance']['percent'];
		$base = round($results['taxes']['base'] / 2, 0, PHP_ROUND_HALF_UP);
		if ($isStudent && $results['taxes']['base'] < $this->cfg['social_insurance']['edge_amount']) {
			$base = 0;
			$results['social_insurance']['insurance'] = 0;
		}
		$results['social_insurance']['base'] = $base;
		
		// see http://www.finance.cz/dane-a-mzda/informace/socialni-pojisteni-osvc/sazby/
		$k = 1; // 25 % ... 1
		if ($isStudent) {
			$k = $this->cfg['social_insurance']['full_to_part_ratio']; // 10 % ... 2.5
		}
		
		$minBase = min(max($base, $this->cfg['social_insurance']['min_base'] / $k), $this->cfg['social_insurance']['max_base']);
		$results['social_insurance']['insurance'] = round(0.292 * $base, 0, PHP_ROUND_HALF_UP);
		
		if ($isStudent && $results['social_insurance']['base'] <= 0) {
			$results['social_insurance']['deposit'] = 0;
		} else {
			$depositBase = min(max($results['taxes']['base'] * 0.5 / 12, $this->cfg['social_insurance']['min_deposit'] / $k), $this->cfg['social_insurance']['max_deposit']);
			$min = $percent * $this->cfg['social_insurance']['min_deposit'] / $k;
			$max = $percent * $this->cfg['social_insurance']['max_deposit'];
			$results['social_insurance']['deposit'] = min(max($depositBase * $percent, $min), $max);
			$results['social_insurance']['deposit'] = round($results['social_insurance']['deposit'], 0, PHP_ROUND_HALF_UP);
		}
		
		// results
		unset($this->cfg['api_key']);
		return $results;
	}
}