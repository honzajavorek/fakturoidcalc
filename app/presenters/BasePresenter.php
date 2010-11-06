<?php

/**
 * FakturoidCalc.
 *
 * @author Jan Javorek <honza@javorek.net>
 * @copyright Copyright (c) 2010 Jan Javorek
 */


/**
 * Base class for all application presenters.
 *
 * @author     John Doe
 * @package    MyApplication
 */
abstract class BasePresenter extends Presenter
{
	protected function startup()
	{
		parent::startup();
		$this->checkRequirements();
	}
	
	private function checkRequirements()
	{
		$version = '5.2.0';
		if (version_compare(PHP_VERSION, $version, '<')) {
			throw new InvalidStateException("Your PHP version number is less than required: $version.");
		}
		if (!extension_loaded('curl')) {
			throw new InvalidStateException("Extension 'curl' not present in your PHP installation.");
		}
	}
}
