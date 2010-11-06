<?php

/**
 * FakturoidCalc.
 *
 * @author Jan Javorek <honza@javorek.net>
 * @copyright Copyright (c) 2010 Jan Javorek
 */


/**
 * Calc presenter.
 *
 * @author Jan Javorek <honza@javorek.net>
 */
class CalcPresenter extends BasePresenter
{

	protected function createComponentMainForm()
	{
		$form = new AppForm($this, 'mainForm');
		
		$form->addSubmit('ok', '✔ Jdi do toho');
		$form->onSubmit[] = callback($this, 'analysisFormSubmitted');
		return $form;
	}
	
	protected 
	
	public function renderDefault()
	{
		
	}

}
