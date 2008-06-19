<?php
	/**
	 * Elgg statistics library.
	 * This file contains a number of functions for obtaining statistics about the running system.
	 * These statistics are mainly used by the administration pages, and is also where the basic views for statistics 
	 * are added.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */


	// number of users
	
	// Number of objects broken down into type
		
	// who' s online now
	
	/**
	 * Return an array reporting the number of various entities in the system.
	 * 
	 * @return array
	 */
	function get_entity_statistics()
	{
		global $CONFIG;
		
		$entity_stats = array();
		
		// Get a list of major types
		$types = get_data("SELECT distinct e.type,s.subtype,e.subtype as subtype_id from {$CONFIG->dbprefix}entities e left join {$CONFIG->dbprefix}entity_subtypes s on e.subtype=s.id");
		foreach ($types as $type) {
			if (!is_array($entity_stats[$type->type])) 
				$entity_stats[$type->type] = array(); // assume there are subtypes for now
			
			$query = "SELECT count(*) as count from {$CONFIG->dbprefix}entities where type='{$type->type}'";
			if ($type->subtype) $query.= " and subtype={$type->subtype_id}";
                         
			$subtype_cnt = get_data_row($query);
			
			if ($type->subtype)
				$entity_stats[$type->type][$type->subtype] = $subtype_cnt->count;
			else
				$entity_stats[$type->type]['__base__'] = $subtype_cnt->count;	
		}
		
		return $entity_stats;
	}
         
	/**
	 * Return the number of users registered in the system.
	 *
	 * @return int
	 */
	function get_number_users()
	{
		global $CONFIG;
            
		$result = get_data_row("SELECT count(*) as count from {$CONFIG->dbprefix}entities where type='user'");
            
		if ($result)
			return $result->count;
            
		return false;
	}
        
	/**
	 * Report how many users are currently online
	 *
	 * @return int
	 */
	function get_number_online()
	{
		// TODO: get logged in users somehow
	}
        
	/**
	 * Return a list of how many users are currently online, rendered as a view.
 	 */
	function get_online_users()
	{
		$objects = find_active_users();
		
		if ($objects)
		{
			return elgg_view_entity_list($objects, count($objects), 0, 10);
		}
	}
        
    /**
	 * Initialise the statistics admin page.
	 */
	function statistics_init()
	{
		extend_elgg_admin_page('admin/statistics_opt/basic', 'admin/statistics');
		extend_elgg_admin_page('admin/statistics_opt/numentities', 'admin/statistics'); 
		extend_elgg_admin_page('admin/statistics_opt/online', 'admin/statistics'); 
	}
	
	/// Register init function
	register_elgg_event_handler('init','system','statistics_init');
?>
