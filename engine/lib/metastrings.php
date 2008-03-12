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
	 * @param string $tag The value (whatever that is) to be stored
	 * @return mixed Integer tag or false.
	 */
	function get_metastring_id($tag)
	{
		global $CONFIG;
		
		$tag = sanitise_string($tag);
		
		$row = get_data_row("SELECT * from {$CONFIG->dbprefix}metastrings where tag='$tag' limit 1");
		if ($row)
			return $row->id;
			
		return false;
	}

	/**
	 * Add a metastring.
	 * It returns the id of the tag, whether by creating it or updating it.
	 * 
	 * @param string $tag The value (whatever that is) to be stored
	 * @return mixed Integer tag or false.
	 */
	function add_metastring($tag)
	{
		global $CONFIG;
		
		$tag = sanitise_string($tag);
		
		$id = get_metastring_id($tag);
		if ($id) return $id;
		
		return insert_data("INSERT into {$CONFIG->dbprefix}metastrings (tag) values ('$tag')");
	}
	
?>