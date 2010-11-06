<?php

// the identification of this site
define('SITE', 'FakturoidCalc');

// absolute filesystem path to the web root
define('WWW_DIR', dirname(__FILE__));

// absolute filesystem path to the application root
define('APP_DIR', WWW_DIR . '/app');

// absolute filesystem path to the libraries
define('LIBS_DIR', APP_DIR . '/libs');

// absolute filesystem path to the temporary files
define('TEMP_DIR', APP_DIR . '/temp');

// load bootstrap file
require APP_DIR . '/bootstrap.php';
