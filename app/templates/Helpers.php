<?php
/**
 * Template helpers.
 *
 * @author Jan Javorek <honza@javorek.net>
 * @copyright Copyright (c) 2010 Jan Javorek
 */

class Helpers
{
	/**
	 * Currency formatter.
	 * 
	 * http://addons.nette.org/cs/helper-currency
	 * 
	 * @author David Grudl
	 * @param mixed $value
	 * @return string
	 */
    public static function currency($value)
    {
    	// FIXME debug safe mode
        return str_replace(' ', "\xc2\xa0", number_format($value, 0, '', ' ')) . "\xc2\xa0Kč";
    }
}