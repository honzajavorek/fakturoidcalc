<?php

/**
 * FakturoidCalc.
 *
 * @author Jan Javorek <honza@javorek.net>
 * @copyright Copyright (c) 2010 Jan Javorek
 */


/**
 * Provides data from invoices.
 *
 * @author Jan Javorek <honza@javorek.net>
 */
class InvoiceModel extends Object
{
	/**
	 * @var string
	 */
	protected $username;
	
	/**
	 * @var string
	 */
	protected $apiKey;
	
	/**
	 * @var DOMXPath[]
	 */
	private $fileCache = array();
	
	/**
	 * @param string $username
	 * @param string $apiKey
	 */
	public function __construct($username, $apiKey)
	{
		$this->username = $username;
		$this->apiKey = $apiKey;
	}
	
	protected function getFile($fileName)
	{
		if (isset($this->fileCache[$fileName])) {
			return $this->fileCache[$fileName];
		}
		$xml = $this->fetch($fileName);
		
		$doc = new DOMDocument();
		$doc->load($xml);
		$this->fileCache[$fileName] = new DOMXPath($doc);
		return $this->fileCache[$fileName];
	}
	
	/**
	 * Fetches wanted file from server.
	 * 
	 * Uses HTTPS authorization and certificate check. See these tutorials:
	 * 	- http://www.electrictoolbox.com/php-curl-sending-username-password/
	 *  - http://unitstep.net/blog/2009/05/05/using-curl-in-php-to-access-https-ssltls-protected-sites/
	 *  - http://www.php.net/manual/en/function.curl-error.php#87212
	 * 
	 * @param string $file
	 * @return string Response, should be XML.
	 */
	private function fetch($fileName)
	{
		$username = $this->username;
		$apiKey = $this->apiKey;
		
		$error = NULL;
		
		$c = curl_init();
		curl_setopt_array($c, array(
			CURLOPT_URL => "https://$username.fakturoid.cz/$fileName", // url
			CURLOPT_RETURNTRANSFER => TRUE, // return response
			CURLOPT_FAILONERROR => TRUE, // HTTP errors
			
			CURLOPT_USERPWD => "$username:$apiKey", // auth
			CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
			
			CURLOPT_SSL_VERIFYPEER => TRUE, // HTTPS, certificate
			CURLOPT_SSL_VERIFYHOST => 2,
			CURLOPT_CAINFO => Resources::get('fakturoid.crt'),
		));
		$response = curl_exec($c);
		if ($response === FALSE) {
			$error = curl_error($c);
		}
		curl_close($c);
		if ($error) {
			throw new IOException($error);
		}
		return $response;
	}
	
}
