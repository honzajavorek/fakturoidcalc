<?php

/**
 * FakturoidCalc.
 *
 * @author Jan Javorek <honza@javorek.net>
 * @copyright Copyright (c) 2010 Jan Javorek
 */


/**
 * Template helpers
 *
 * @author Jan Javorek <honza@javorek.net>
 */
class Template
{
	private $cli;
	
	public function __construct()
	{
		$this->cli = (php_sapi_name() == 'cli');
	}
	
	public function getFiles()
	{
		$files = array();
		foreach (glob(dirname(__FILE__) . '/../data/*.ini') as $filename) {
		  if (is_file($filename)) {
		  	$files[] = basename($filename);
		  }
		}
		return $files;
	}
	
	public function currency($value)
    {
        return str_replace(' ', "\xc2\xa0", number_format(round($value, 0, PHP_ROUND_HALF_UP), 0, '', ' ')) . "\xc2\xa0Kč";
    }
}
