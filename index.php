<?php
/**
 * Init.
 *
 * @author Jan Javorek <honza@javorek.net>
 * @copyright Copyright (c) 2010 Jan Javorek
 */

function __autoload($className) {
	include_once dirname(__FILE__) . "/app/$className.php";
}

$app = new Application;
$app->run();
