<?php

/**
 * Bootstrap file
 *
 * @author Jan Javorek <honza@javorek.net>
 * @copyright Copyright (c) 2010 Jan Javorek
 */

// requirements
if (!extension_loaded('curl')) {
	throw new Exception("Abych správně fungoval, musíš mít v PHP nainstalované 'curl'.");
}

// autoload
function __autoload($class)
{
	include_once dirname(__FILE__) . '/' . $class . '.php';
}

// helpers
function dump($var)
{
	var_dump($var);
}
