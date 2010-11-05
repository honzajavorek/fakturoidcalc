<?php
/**
 * Prototype.
 *
 * @author Jan Javorek <honza@javorek.net>
 * @copyright Copyright (c) 2010 Jan Javorek
 */

if (version_compare(PHP_VERSION, '5.2.0', '<')) {
	echo 'Fail, too old PHP.';
}

if (!extension_loaded('curl')) {
	echo 'Fail, no curl';
}

$username = 'littlemaple';
$apiKey = trim(file_get_contents(dirname(__FILE__) . '/key.txt'));

$username = trim($username); // dummy sanitation

/*
 * For certificate handling, see:
 * 
 * http://www.electrictoolbox.com/php-curl-sending-username-password/
 * http://unitstep.net/blog/2009/05/05/using-curl-in-php-to-access-https-ssltls-protected-sites/
 */

$c = curl_init();
curl_setopt_array($c, array(
	CURLOPT_URL => "https://$username.fakturoid.cz/invoices.xml", // url
	CURLOPT_RETURNTRANSFER => TRUE, // return response
	
	CURLOPT_USERPWD => "$username:$apiKey",
	CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
	
	CURLOPT_SSL_VERIFYPEER => TRUE,
	CURLOPT_SSL_VERIFYHOST => 2,
	CURLOPT_CAINFO => dirname(__FILE__) . '/fakturoid.crt',
));
$response = curl_exec($c);
$info = curl_getinfo($c);
curl_close($c);

echo '<pre>';
var_dump($info);
echo $response . '</pre>';
