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
		
		$this->template->registerHelper('currency', 'Helpers::currency');
	}
	
	private function checkRequirements()
	{
		if (!extension_loaded('curl')) {
			throw new InvalidStateException("Extension 'curl' not present in your PHP installation.");
		}
	}
}
