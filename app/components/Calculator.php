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
class Calculator extends Component
{
	protected $options = array();
	
	/**
	 * @var FakturoidModel
	 */
	protected $model;
	
	public function __construct(array $options = array())
	{
		$this->initOptions($options);
	}
	
	/**
	 * Initializes options.
	 * 
	 * @param array $options
	 * @return void
	 */
	protected function initOptions(array $options = array())
	{
		// hardcoded defaults, TODO move it into XML/JSON config file
		$this->options = array(
			'expenses' => 60,
			'employmentForm' => 'student',
			'tax' => 15,
			'reductions' => array(
				'default' => array(2009 => 24840, 2010 => 24840),
				'child' => array(2009 => 10680, 2010 => 11604),
				'student' => array(2009 => 4020, 2010 => 4020),
				'spouse' => array(2009 => 24840, 2010 => 24840),
			),
			'minHealthInsuranceBase' => array(2009 => 11777.50, 2010 => 11854.50),
			'maxHealthInsuranceBase' => array(2009 => 1130640, 2010 => 1707048),
			'minHealthInsuranceDeposit' => array(2009 => 1601, 2010 => 1601),
			'maxHealthInsuranceDeposit' => array(2009 => 19205, 2010 => 19205),
			'socialInsuranceEdge' => array(2009 => 56532, 2010 => 56901, 2011 => 59374),
			'minSocialInsuranceBase' => array(2009 => 70668, 2010 => 71136, 2011 => 74220),
			'maxSocialInsuranceBase' => array(2009 => 1130640, 2010 => 1707048, 2011 => 178128),
			'minSocialInsuranceDeposit' => array(2009 => NULL, 2010 => 5928, 2011 => 6185),
			'maxSocialInsuranceDeposit' => array(2009 => NULL, 2010 => 142254, 2011 => 148440),
		);
		
		// years
		$current = (int)date('Y');
		$this->options['years'] = ((int)date('n') <= 3)? $current - 1 : $current;
		
		// overriding
		$this->options = array_merge($this->options, $options);
	}

	/**
	 * Options getter.
	 * 
	 * @return array
	 */
	public function getOptions()
	{
		return $this->options;
	}
	
	/**
	 * Lazy model provider.
	 * 
	 * @return FakturoidModel
	 */
	protected function getModel()
	{
		if (!$this->model) {
			if (empty($this->options['username']) || empty($this->options['apiKey'])) {
				throw new InvalidArgumentException('Username or API key missing.');
			}
			$this->model = new FakturoidModel($this->options['username'], $this->options['apiKey']);
		}
		return $this->model;
	}
	
	public function calculateTaxes()
	{
		$results = array();
		$year = $this->options['year'];
		
		$results['income'] = ceil($this->getModel()->getIncome($year));
		$results['expenses'] = ceil($results['income'] * ($this->options['expenses'] / 100));
		$results['profit'] = ceil($results['income'] - $results['expenses']);
		$results['taxBase'] = ceil(round($results['profit'], -2, PHP_ROUND_HALF_DOWN));
		$results['tax'] = $results['taxBase'] * ($this->options['tax'] / 100);
		
		// reductions
		$results['reducedTax'] = $results['tax'] - $this->options['reductions']['default'][$year];
		if ($this->options['employmentForm'] == 'student') {
			$results['reducedTax'] -= $this->options['reductions']['student'][$year];
		}
		
		$results['finalTax'] = ($results['reducedTax'] < 0)? 0 : $results['reducedTax'];
		
		return $results;
	}
	
	public function calculateHealthInsurance()
	{
		$results = array();
		$year = $this->options['year'];
		
		$income = ceil($this->getModel()->getIncome($year));
		$expenses = ceil($income * ($this->options['expenses'] / 100));
		$profit = ceil($income - $expenses);
		
		$minBaseMonthCount = ($this->options['employmentForm'] == 'student')? 0 : 12;
		$base = min(max($profit * 0.5, $minBaseMonthCount * $this->options['minHealthInsuranceBase'][$year]), $this->options['maxHealthInsuranceBase'][$year]);
		$results['base'] = ($base < 0)? 0 : $base;
		
		$results['insurance'] = 0.0675 * $results['base'];
		
		// deposit
		$results['deposit'] = min((0.0675 * $profit) / 12, $this->options['maxHealthInsuranceDeposit'][$year]);
		if ($this->options['employmentForm'] != 'student') {
			$results['deposit'] = max($results['deposit'], $this->options['minHealthInsuranceDeposit'][$year]);
		}
		$results['deposit'] = round($results['deposit'], 0, PHP_ROUND_HALF_UP);
		
		return $results;
	}
	
	public function calculateSocialInsurance()
	{
		$results = array();
		$year = $this->options['year'];
		
		$taxes = $this->calculateTaxes($year);
		
		$base = round($taxes['taxBase'] / 2, 0, PHP_ROUND_HALF_UP);
		if ($this->options['employmentForm'] == 'student' && $taxes['taxBase'] >= $this->options['socialInsuranceEdge']) {
			$base = 0;
			$results['base'] = $results['insurance'] = 0;
		}
		$results['base'] = $base;
		
		// see http://www.finance.cz/dane-a-mzda/informace/socialni-pojisteni-osvc/sazby/
		$k = 1; // 25 %
		if ($this->options['employmentForm'] == 'student') {
			$k = 2.5; // 10 %
		}
		
		// insurance
		$minBase = min(max($base, $this->options['minSocialInsuranceDeposit'][$year] / $k), $this->options['maxSocialInsuranceDeposit'][$year]);
		$results['insurance'] = round(0.292 * $base, 0, PHP_ROUND_HALF_UP);
		
		// deposit
		if ($this->options['employmentForm'] == 'student' && $results['base'] <= 0) {
			$results['deposit'] = 0;
		} else {
			$depositBase = min(max($taxes['taxBase'] * 0.5 / 12, $this->options['minSocialInsuranceDeposit'][$year] / $k), $this->options['maxSocialInsuranceDeposit'][$year]);
			$min = 0.292 * $this->options['minSocialInsuranceDeposit'][$year] / $k;
			$max = 0.292 * $this->options['maxSocialInsuranceDeposit'][$year];
			$results['deposit'] = min(max($depositBase * 0.292, $min), $max);
			$results['deposit'] = round($results['deposit'], 0, PHP_ROUND_HALF_UP);
		}
		
		return $results;
	}
}