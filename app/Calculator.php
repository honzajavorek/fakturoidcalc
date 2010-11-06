<?php
/**
 * Calculates all the dirty numbers.
 *
 * @author Jan Javorek <honza@javorek.net>
 * @copyright Copyright (c) 2010 Jan Javorek
 */
class Calculator {
	
	/**
	 * @var DOMDocument
	 */
	protected $xml;
	
	/**
	 * @var DOMXPath
	 */
	protected $xpath;
	
	/**
	 * @var array
	 */
	protected $options = array(
		'' => '',
	);
	
	public function __construct($xml, $options = array()) {
		$doc = new DOMDocument();
		$doc->load($xml);
		$this->xml = $doc;
		$this->xpath = new DOMXPath($this->xml);
		$this->options = array_merge($this->options, $options);
	}
	
}
