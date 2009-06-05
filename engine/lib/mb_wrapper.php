<?php
	/**
	 * Elgg wrapper functions for multibyte string support.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
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
	
	// TODO: Other wrapper functions
?>