<?php

/**
 * FakturoidCalc.
 *
 * @author Jan Javorek <honza@javorek.net>
 * @copyright Copyright (c) 2010 Jan Javorek
 */



/**
 * Error presenter.
 *
 * @author Jan Javorek <honza@javorek.net>
 */
class ErrorPresenter extends BasePresenter
{

	/**
	 * @param  Exception
	 * @return void
	 */
	public function renderDefault($exception)
	{
		if ($this->isAjax()) { // AJAX request? Just note this error in payload.
			$this->payload->error = TRUE;
			$this->terminate();

		} elseif ($exception instanceof BadRequestException) {
			$code = $exception->getCode();
			$this->setView('4xx'); // load template 4xx.phtml

		} else {
			$this->setView('500'); // load template 500.phtml
			Debug::log($exception, Debug::ERROR); // and log exception
		}
	}

}
