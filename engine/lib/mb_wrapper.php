<?php
	/**
	 * Elgg wrapper functions for multibyte string support.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @author Curverider Ltd
	 * @link http://elgg.org/
	 */

	/**
	 * Wrapper function: Returns the result of mb_strtolower if mb_support is present, else the
	 * result of strtolower is returned.
	 *
	 * @param string $string The string.
	 * @param string $charset The charset (if multibyte support is present) : default 'UTF8'
	 * @return string
	 */
	function elgg_strtolower($string, $charset = 'UTF8')
	{
		if (is_callable('mb_strtolower'))
			return mb_strtolower($string, $charset);
			
		return strtolower($string);
	}
	
	/**
	 * Wrapper function: Returns the result of mb_strtoupper if mb_support is present, else the
	 * result of strtoupper is returned.
	 *
	 * @param string $string The string.
	 * @param string $charset The charset (if multibyte support is present) : default 'UTF8'
	 * @return string
	 */
	function elgg_strtoupper($string, $charset = 'UTF8')
	{
		if (is_callable('mb_strtoupper'))
			return mb_strtoupper($string, $charset);
			
		return strtoupper($string);
	}
	
	/**
	 * Wrapper function: Returns the result of mb_substr if mb_support is present, else the 
	 * result of substr is returned.
	 *
	 * @param string $string The string.
	 * @param int $start Start position.
	 * @param int $length Length.
	 * @param string $charset The charset (if multibyte support is present) : default 'UTF8'
	 * @return string
	 */
	function elgg_substr($string, $start = 0, $length = null, $charset = 'UTF8')
	{
		if (is_callable('mb_substr'))
			return mb_substr($string, $start, $length, $charset);
		
		return substr($string, $start, $length);
	}
	
	// TODO: Other wrapper functions
?>