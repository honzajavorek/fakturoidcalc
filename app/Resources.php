<?php
/**
 * Resources.
 *
 * @author Jan Javorek <honza@javorek.net>
 * @copyright Copyright (c) 2010 Jan Javorek
 */
final class Resources {
	
	public static function get($name) {
		return dirname(__FILE__) . '/../res/' . $name;
	}
	
	public static function getContents($name) {
		return file_get_contents(self::get($name));
	}
	
}
