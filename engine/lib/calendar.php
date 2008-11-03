<?php

	/**
	 * Elgg calendar / entity / event functions.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * Calendar interface for events.
	 *
	 */
	interface Noteable {
	
		/**
		 * Calendar functionality.
		 * This function sets the time of an object on a calendar listing.
		 *
		 * @param int $hour If ommitted, now is assumed.
		 * @param int $minute If ommitted, now is assumed.
		 * @param int $second If ommitted, now is assumed.
		 * @param int $day If ommitted, now is assumed.
		 * @param int $month If ommitted, now is assumed.
		 * @param int $year If ommitted, now is assumed.
		 * @param int $duration Duration of event, remainder of the day is assumed.
		 */
		public function setCalendarTimeAndDuration($hour = NULL, $minute = NULL, $second = NULL, $day = NULL, $month = NULL, $year = NULL, $duration = NULL);
		
		/**
		 * Return the start timestamp.
		 */
		public function getCalendarStartTime();
		
		/**
		 * Return the end timestamp.
		 */
		public function getCalendarEndTime();
	}
	
	
	
	// All of these should either implemented in existing functions (with extra params) or new funcs
		// get entities by time 
		// get entities by metadata
		// get entities by relationship
		
	// Implement get/set via metadata
	
	
	/**
	 * Return a timestamp for the start of a given day (defaults today).
	 *
	 */
	function get_day_start($day = null, $month = null, $year = null) { return mktime(0,0,0,$month,$day,$year); }
	
	/**
	 * Return a timestamp for the end of a given day (defaults today).
	 *
	 */
	function get_day_end($day = null, $month = null, $year = null) { return mktime(23,59,59,$month,$day,$year); }
	
	/**
	 * Get all entities for today.
	 *
	 * @param string $type The type of entity (eg "user", "object" etc)
	 * @param string $subtype The arbitrary subtype of the entity
	 * @param int $owner_guid The GUID of the owning user
	 * @param string $order_by The field to order by; by default, time_created desc
	 * @param int $limit The number of entities to return; 10 by default
	 * @param int $offset The indexing offset, 0 by default
	 * @param boolean $count Set to true to get a count rather than the entities themselves (limits and offsets don't apply in this context). Defaults to false.
	 * @param int $site_guid The site to get entities for. Leave as 0 (default) for the current site; -1 for all sites.
	 * @param int|array $container_guid The container or containers to get entities from (default: all containers).
	 */
	function get_todays_entities($type = "", $subtype = "", $owner_guid = 0, $order_by = "", $limit = 10, $offset = 0, $count = false, $site_guid = 0, $container_guid = null)
	{
		$day_start = get_day_start();
		$day_end = get_day_end();
		
		
		/// TODO
	}
	
	/**
	 * Get entities for today from metadata.
	 *
	 * @param mixed $meta_name 
	 * @param mixed $meta_value
	 * @param string $entity_type The type of entity to look for, eg 'site' or 'object'
	 * @param string $entity_subtype The subtype of the entity.
	 * @param int $limit 
	 * @param int $offset
	 * @param string $order_by Optional ordering.
	 * @param int $site_guid The site to get entities for. Leave as 0 (default) for the current site; -1 for all sites.
	 * @param true|false $count If set to true, returns the total number of entities rather than a list. (Default: false)
	 * 
	 * @return int|array A list of entities, or a count if $count is set to true
	 */
	function get_todays_entities_from_metadata($meta_name, $meta_value = "", $entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $order_by = "", $site_guid = 0, $count = false)
	{
		$day_start = get_day_start();
		$day_end = get_day_end();
		
		// TODO
	}
	
	/**
	 * Get entities for today from a relationship
	 *
	 * @param string $relationship The relationship eg "friends_of"
	 * @param int $relationship_guid The guid of the entity to use query
	 * @param bool $inverse_relationship Reverse the normal function of the query to instead say "give me all entities for whome $relationship_guid is a $relationship of"
	 * @param string $type 
	 * @param string $subtype
	 * @param int $owner_guid
	 * @param string $order_by
	 * @param int $limit
	 * @param int $offset
	 * @param boolean $count Set to true if you want to count the number of entities instead (default false)
	 * @param int $site_guid The site to get entities for. Leave as 0 (default) for the current site; -1 for all sites.
	 * @return array|int|false An array of entities, or the number of entities, or false on failure
	 */
	function get_todays_entities_from_relationship($relationship, $relationship_guid, $inverse_relationship = false, $type = "", $subtype = "", $owner_guid = 0, $order_by = "", $limit = 10, $offset = 0, $count = false, $site_guid = 0)
	{
		$day_start = get_day_start();
		$day_end = get_day_end();
		
		// TODO
	}
?>