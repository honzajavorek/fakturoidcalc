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
		
		// years
		$current = (int)date('Y');
		$years = array(
			$current,
			($current - 1),
		);
		$form->addSelect('year', 'Rok:', $years)->setDefaultValue(((int)date('n') <= 3)? $current - 1 : $current);
		
		$form->addSubmit('ok', 'Jdi do toho, nebojím se!');
		$form->onSubmit[] = callback($this, 'mainFormSubmitted');
		return $form;
	}
	
	public function mainFormSubmitted(Form $form)
	{
		$this->redirect('this');
	}
	
	public function renderDefault()
	{
		
	}

}
