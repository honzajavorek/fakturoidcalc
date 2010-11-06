<?php
/**
 * FakturoidCalc bootstrap file.
 *
 * @author Jan Javorek <honza@javorek.net>
 * @copyright Copyright (c) 2010 Jan Javorek
 */

require LIBS_DIR . '/Nette/loader.php';

Debug::$strictMode = TRUE;
Debug::enable();

Environment::loadConfig();

$application = Environment::getApplication();
$application->errorPresenter = 'Error';

$router = $application->getRouter();

$router[] = new Route('index.php', array(
	'presenter' => 'Calc',
	'action' => 'default',
), Route::ONE_WAY);

$router[] = new Route('<presenter>/<action>/<id>', array(
	'presenter' => 'Calc',
	'action' => 'default',
	'id' => NULL,
));

$application->run();
