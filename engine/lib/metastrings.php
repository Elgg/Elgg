<?php
	/**
	 * Elgg metastrngs
	 * Functions to manage object metastrings.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * Return the meta string id for a given tag, or false.
	 * 
	 * @param string $string The value (whatever that is) to be stored
	 * @return mixed Integer tag or false.
	 */
	function get_metastring_id($string)
	{
		global $CONFIG;
		
		$string = sanitise_string($string);
		
		$row = get_data_row("SELECT * from {$CONFIG->dbprefix}metastrings where string='$string' limit 1");
		if ($row)
			return $row->id;
			
		return false;
	}

	/**
	 * Add a metastring.
	 * It returns the id of the tag, whether by creating it or updating it.
	 * 
	 * @param string $string The value (whatever that is) to be stored
	 * @return mixed Integer tag or false.
	 */
	function add_metastring($string)
	{
		global $CONFIG;
		
		$string = sanitise_string($string);
		
		$id = get_metastring_id($string);
		if ($id) return $id;
		
		return insert_data("INSERT into {$CONFIG->dbprefix}metastrings (string) values ('$string')");
	}
	
?>