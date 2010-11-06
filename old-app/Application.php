<?php
/**
 * Application.
 *
 * @author Jan Javorek <honza@javorek.net>
 * @copyright Copyright (c) 2010 Jan Javorek
 */
final class Application {
	
	public function __construct() {
		try {
			$req = new RequirementsChecker();
			$req->checkPHPVersion('5.2.0');
			$req->checkPHPExtension('curl');
		} catch (RequirementsException $e) {
			echo 'Req fail.'; // TODO
		}
	}
	
	private function renderTemplate($name, array $params = array()) {
		extract($params);
		include dirname(__FILE__) . '/../tpl/' . $name;
	}
	
	public function run() {
		$this->renderTemplate('header.tpl.php');
		
		if (!empty($_POST)) {
			$this->process();
		} else {
			$this->renderForm();
		}
		
		$this->terminate();
	}
	
	private function renderForm($errors = array()) {
		// years
		$current = (int)date('Y');
		$years = array(
			$current => TRUE,
			($current - 1) => TRUE,
		);
		if ((int)date('n') <= 3) { // March trick
			$years[$current] = FALSE;
		} else {
			$years[$current - 1] = FALSE;
		}
		
		// expenses
		$expenses = array(
			80 => FALSE,
			60 => TRUE,
			40 => FALSE,
			30 => FALSE,
		);
		
		// render
		$this->renderTemplate('form.tpl.php', array(
			'errors' => (array)$errors,
			'years' => $years,
			'expenses' => $expenses,
		));
	}
	
	private function renderError($message) {
		$this->renderTemplate('error.tpl.php', array('message' => $message));
	}
	
	private function process() {
		$username = (string)trim($_POST['username']);
		$apiKey = (string)trim($_POST['apiKey']);
		
		if (!$username || !$apiKey) {
			$this->renderForm('Tož ale s prázdným jménem nebo klíčem toho moc nevymyslím.');
			$this->terminate();
		}
		
		try {
			$f = new Fetcher($username, $apiKey);
			$xml = $f->fetch('invoices.xml');
			
			var_dump($xml);
			
		} catch (FetchingException $e) {
			$this->renderForm('Hm, něco se pokazilo při pokusu o získání seznamu faktur. Nemáš chybně jméno nebo klíč?');
			$this->terminate();
		}
	}
	
	private function terminate() {
		$this->renderTemplate('footer.tpl.php');
		exit;
	}
	
}