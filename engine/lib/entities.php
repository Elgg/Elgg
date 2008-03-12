<?php
	/**
	 * Elgg entities.
	 * Functions to manage all elgg entities (sites, collections, objects and users).
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * Get the subtype of a given entity, based on its id and type pair.
	 * 
	 * @param int $entity_id The entity ID
	 * @param string $entity_type The entity type string.
	 * @return mixed Either an int id into the *_entity_subtypes
	 */
	function get_entity_subtype($entity_id, $entity_type)
	{
		global $CONFIG;
		
		$entity_id = (int)$entity_id;
		$entity_type = sanitise_string($entity_type);
		
		$row = get_data_row("SELECT * from {$CONFIG->dbprefix}entity_subtypes where entity_id=$entity_id and entity_type='$entity_type' limit 1");
		if ($row)
			return $row->id;
		
		return false;
	}
?>