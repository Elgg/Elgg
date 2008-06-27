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

	/** Cache metastrings for a page */
	$METASTRINGS_CACHE = array();

	/**
	 * Return the meta string id for a given tag, or false.
	 * 
	 * @param string $string The value (whatever that is) to be stored
	 * @return mixed Integer tag or false.
	 */
	function get_metastring_id($string)
	{
		global $CONFIG, $METASTRINGS_CACHE;
		
		$string = sanitise_string($string);
		$result = array_search($string, $METASTRINGS_CACHE);
		if ($result!==false)
			return $result;
			
		$row = get_data_row("SELECT * from {$CONFIG->dbprefix}metastrings where string='$string' limit 1");
		if ($row) { 
			$METASTRINGS_CACHE[$row->id] = $row->string; // Cache it
			if ($CONFIG->debug)
				error_log("Returning id for string {$row->string} from cache.");
				
			return $row->id;
		}
			
		return false;
	}
	
	/**
	 * When given an ID, returns the corresponding metastring
	 *
	 * @param int $id Metastring ID
	 * @return string Metastring
	 */
	function get_metastring($id) {
		
		global $CONFIG, $METASTRINGS_CACHE;
		
		$id = (int) $id;
		
		if (isset($METASTRINGS_CACHE[$id]))
			return $METASTRINGS_CACHE[$id];
		
		$row = get_data_row("SELECT * from {$CONFIG->dbprefix}metastrings where id='$id' limit 1");
		if ($row) {
			$METASTRINGS_CACHE[$id] = $row->string; // Cache it
			if ($CONFIG->debug)
				error_log("Returning string {$row->string} from cache.");
			
			return $row->string;
		}
			
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
		global $CONFIG, $METASTRINGS_CACHE;
		
		$sanstring = sanitise_string($string);
		
		$id = get_metastring_id($string);
		if ($id) return $id;
		
		$result = insert_data("INSERT into {$CONFIG->dbprefix}metastrings (string) values ('$sanstring')");
		if ($result)
			$METASTRINGS_CACHE[$result] = $string;
			
		return $result;
	}
	
?>