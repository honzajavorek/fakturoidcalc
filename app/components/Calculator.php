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
		// defaults
		$this->options = array(
			'expenses' => '60',
			'student' => TRUE,
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
	
}