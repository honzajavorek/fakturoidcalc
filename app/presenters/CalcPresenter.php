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
		
		// auth
		$form->addText('username', 'Uživatelské jméno:')
			->addRule(Form::FILLED, 'Tož ale bez jména toho moc nevymyslím.');
		$form->addText('apiKey', 'API klíč:')
			->addRule(Form::FILLED, 'Bez klíče se nikam nedostanu.');
			
		// years
		$current = (int)date('Y');
		$years = array(
			$current => $current,
			($current - 1) => ($current - 1),
		);
		$form->addSelect('year', 'Rok:', $years);
		
		// expenses
		$form->addSelect('expenses', 'Výdaje:', array(
			80 => '80 %',
			60 => '60 %',
			40 => '40 %',
			30 => '30 %',
		));
		
		// student
		$form->addCheckbox('student', 'Student:');
		
		// defaults
		$calc = new Calculator;
		$form->setDefaults($calc->getOptions());
		
		$form->addSubmit('ok', 'Jdi do toho!');
		$form->onSubmit[] = callback($this, 'mainFormSubmitted');
		return $form;
	}
	
	public function mainFormSubmitted(Form $form)
	{
		$options = $form->getValues();
		$calc = new Calculator($options);
		
		// TODO calculation
		
		$this->redirect('Calc:results');
	}
	
	public function renderDefault()
	{
		$this->template->form = $this['mainForm'];
	}

}
