<?php
	/**
	 * Elgg activity stream.
	 * Functions for listening for and generating the rich activity stream from the 
	 * system log.
	 * 
	 * @package Elgg
	 * @subpackage Core

	 * @author Curverider Ltd

	 * @link http://elgg.org/
	 */

	/**
	 * Construct and execute the query required for the activity stream.
	 *
	 * @param int $limit Limit the query.
	 * @param int $offset Execute from the given object
	 * @param mixed $type A type, or array of types to look for. Note: This is how they appear in the SYSTEM LOG.
	 * @param mixed $subtype A subtype, or array of types to look for. Note: This is how they appear in the SYSTEM LOG.
	 * @param mixed $owner_guid The guid or a collection of GUIDs
	 * @param string $owner_relationship If defined, the relationship between $owner_guid and the entity owner_guid - so "is $owner_guid $owner_relationship with $entity->owner_guid"
	 * @return array An array of system log entries.
	 */
	function get_activity_stream_data($limit = 10, $offset = 0, $type = "", $subtype = "", $owner_guid = "", $owner_relationship = "")
	{
		global $CONFIG;
		
		$limit = (int)$limit;
		$offset = (int)$offset;
	
		if ($type) {
			if (!is_array($type))
				$type = array(sanitise_string($type));
			else
				foreach ($type as $k => $v)
					$type[$k] = sanitise_string($v);
		}
		
		if ($subtype) {
			if (!is_array($subtype))
				$subtype = array(sanitise_string($subtype));
			else
				foreach ($subtype as $k => $v)
					$subtype[$k] = sanitise_string($v);
		}
		
		if ($owner_guid) {
			if (is_array($owner_guid))
				foreach ($owner_guid as $k => $v)
					$owner_guid[$k] = (int)$v;
			else
				$owner_guid = array((int)$owner_guid);
		}
			
		$owner_relationship = sanitise_string($owner_relationship);
		
		// Get a list of possible views
		$activity_events= array(); 
		$activity_views = array_merge(elgg_view_tree('activity', 'default'), elgg_view_tree('river', 'default')); // Join activity with river

		$done = array();
			
		foreach ($activity_views as $view)
		{
			$fragments = explode('/', $view);
			$tmp = explode('/',$view, 2);
			$tmp = $tmp[1];
			
			if ((isset($fragments[0])) && (($fragments[0] == 'river') || ($fragments[0] == 'activity')) 
				&& (!in_array($tmp, $done)))
			{
				if (isset($fragments[1]))
				{
					$f = array();
					for ($n = 1; $n < count($fragments); $n++)
					{
						$val = sanitise_string($fragments[$n]);
						switch($n)
						{
							case 1: $key = 'type'; break;
							case 2: $key = 'subtype'; break;
							case 3: $key = 'event'; break;
						}
						$f[$key] = $val;
					}
					
					// Filter result based on parameters
					$add = true;
					if ($type) {
						if (!in_array($f['type'], $type)) $add = false;
					}
					if (($add) && ($subtype)) {
						if (!in_array($f['subtype'], $subtype)) $add = false;
					}
					if (($add) && ($event)) {
						if (!in_array($f['event'], $event)) $add = false;
					}
					
					if ($add)
						$activity_events[] = $f;
				}
				
				$done[] = $tmp; 
			}
			
			
		}

		$n = 0;
		foreach ($activity_events as $details)
		{
			// Get what we're talking about
		
			if ($details['subtype'] == 'default') $details['subtype'] = '';
			
			if (($details['type']) && ($details['event'])) {
				if ($n>0) $obj_query .= " or ";
				
				$access = "";
				if ($details['type']!='relationship')
					$access = " and " . get_access_sql_suffix('sl');
				 
				$obj_query .= "( sl.object_type='{$details['type']}' and sl.object_subtype='{$details['subtype']}' and sl.event='{$details['event']}' $access )";
				
				$n++;
			}
		
		}		
	
		// User
		if ((count($owner_guid)) &&  ($owner_guid[0]!=0)) {
			$user = " and sl.performed_by_guid in (".implode(',', $owner_guid).")";
			
			if ($owner_relationship)
			{
				$friendsarray = "";
				if ($friends = get_entities_from_relationship($owner_relationship,$owner_guid[0],false,"user",$subtype,0,"time_created desc",9999)) {
					$friendsarray = array();
					foreach($friends as $friend) {
						$friendsarray[] = $friend->getGUID();
					}
					
					$user = " and sl.performed_by_guid in (".implode(',', $friendsarray).")";
				}
				
			}
		}
		
		$query = "SELECT sl.* from {$CONFIG->dbprefix}system_log sl  where 1 $user and ($obj_query) order by sl.time_created desc  limit $offset, $limit";
		return get_data($query);
	}
?>