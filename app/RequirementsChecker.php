<?php
/**
 * Requirements exception.
 */
class RequirementsException extends Exception {
}

/**
 * Requirements checker.
 *
 * @author Jan Javorek <honza@javorek.net>
 * @copyright Copyright (c) 2010 Jan Javorek
 */
class RequirementsChecker {
	
	public function checkPHPVersion($version) {
		if (version_compare(PHP_VERSION, $version, '<')) {
			throw new RequirementsException("Your PHP version number is less than required: $extension.");
		}
		return TRUE;
	}
	
	public function checkPHPExtension($extension) {
		if (!extension_loaded($extension)) {
			throw new RequirementsException("Extension '$extension' not present in your PHP installation.");
		}
		return TRUE;
	}
	
}