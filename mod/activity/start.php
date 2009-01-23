<?php
	/**
	 * Elgg activity plugin.
	 * 
	 * @package ElggActivity
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	/**
	 * Initialise the activity.
	 *
	 */
	function activity_init()
	{
		global $CONFIG;
		
		// Register and optionally replace the dashboard
		if (get_plugin_setting('useasdashboard', 'activity') == 'yes')
			register_page_handler('dashboard','activity_page_handler');
		
		// Page handler
		register_page_handler('activity','activity_page_handler');
		
		// Add our CSS
		extend_view('css','activity/css');
		
		// Activity main menu
		if (isloggedin())
		{
			add_menu(elgg_echo('activity'), $CONFIG->wwwroot . "pg/activity/{$_SESSION['user']->username}/", array(), 'activity');
		}
	}
	
	/**
	 * Post init gumph.
	 */
	function activity_page_setup()
	{
		global $CONFIG;
		
		if ((get_context()=='activity') || (get_context()=='dashboard'))
		{
			add_submenu_item(elgg_echo('activity:your'), $CONFIG->wwwroot."pg/activity/{$_SESSION['user']->username}/");
			add_submenu_item(elgg_echo('activity:friends'), $CONFIG->wwwroot."pg/activity/{$_SESSION['user']->username}/friends/");
			add_submenu_item(elgg_echo('activity:all'), $CONFIG->wwwroot."pg/activity/");
		}
	}
	
	/**
	 * Page handler for activity.
	 *
	 * @param unknown_type $page
	 */
	function activity_page_handler($page)
	{
		global $CONFIG;
		
		if (($page[0]) && ($page[1]!='uuid')) set_input('username', $page[0]);
		
		if ($page[0]) {
			if ($page[1])
			{
				switch ($page[1])
				{
					case 'friends' :
						include($CONFIG->pluginspath . "activity/friends.php");
					break;
					case 'uuid' : // Endpoint for UUID export of statements
						switch ($page[2])
						{
							case 'metadata' : set_input('metaname', $page[4]);
							case 'statement' : set_input('statement_id', $page[3]);
											  set_input('statement_type', $page[2]);
											  elgg_set_viewtype('opendd');
						}
						
						include($CONFIG->pluginspath . "activity/opendd.php");	
					break;
				}
			}
			else
				include($CONFIG->pluginspath . "activity/index.php");	
		} else
			include($CONFIG->pluginspath . "activity/all.php");
	}
	
	/**
	 * Construct and execute the query required for the activity stream.
	 *
	 * @param int $limit Limit the query.
	 * @param int $offset Execute from the given object
	 * @param mixed $type A type, or array of types to look for. Note: This is how they appear in the SYSTEM LOG.
	 * @param mixed $subtype A subtype, or array of types to look for. Note: This is how they appear in the SYSTEM LOG.
	 * @param mixed $owner_guid The guid or a collection of GUIDs
	 * @param string $owner_relationship If defined, the relationship between $owner_guid and the entity owner_guid - so "is $owner_guid $owner_relationship with $entity->owner_guid"
	 * @return array An array of pre-rendered elgg_views on the data.
	 */
	function activity_get_activity_data($limit = 10, $offset = 0, $type = "", $subtype = "", $owner_guid = "", $owner_relationship = "" )
	{
		global $CONFIG;
		
		$limit = (int)$limit;
		$offset = (int)$offset;
	
		if (!is_array($type))
			$type = array(sanitise_string($type));
		else
			foreach ($type as $k => $v)
				$type[$k] = sanitise_string($v);
		
		if (!is_array($subtype))
			$subtype = array(sanitise_string($subtype));
		else
			foreach ($subtype as $k => $v)
				$subtype[$k] = sanitise_string($v);
		
		if (is_array($owner_guid))
			foreach ($owner_guid as $k => $v)
				$owner_guid[$k] = (int)$v;
		else
			$owner_guid = array((int)$owner_guid);
			
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
	
	/**
	 * Pull activity from the system log.
	 * 
	 * This works in a similar way to the river code, but looks for activity views instead.
	 *
	 * @param int $limit Limit the query.
	 * @param int $offset Execute from the given object
	 * @param mixed $type A type, or array of types to look for. Note: This is how they appear in the SYSTEM LOG.
	 * @param mixed $subtype A subtype, or array of types to look for. Note: This is how they appear in the SYSTEM LOG.
	 * @param mixed $owner_guid The guid or a collection of GUIDs
	 * @param string $owner_relationship If defined, the relationship between $owner_guid and the entity owner_guid - so "is $owner_guid $owner_relationship with $entity->owner_guid"
	 * @return array An array of pre-rendered elgg_views on the data.
	 */
	function activity_get_activity($limit = 10, $offset = 0, $type = "", $subtype = "", $owner_guid = "", $owner_relationship = "" )
	{
		global $CONFIG;
		
		$log_data = activity_get_activity_data($limit, $offset, $type, $subtype, $owner_guid, $owner_relationship);

		// until count reached, loop through and render
		$activity = array();
		
		if ($log_data)
		{
			foreach ($log_data as $log)
			{
				// See if we have access to the object we're talking about
				$statement = construct_riverstatement_from_log($log);
				
				$event = $log->event;
				$class = $log->object_class;
				$type = $log->object_type;
				$subtype = $log->object_subtype;
				$tmp = new $class();
				$object = $tmp->getObjectFromID($log->object_id);	
				$by_user_obj = get_entity($log->performed_by_guid);
				
				// Belts and braces
				if ($statement)
				{
					$tam = "";
					
					// Now construct and call the appropriate views
					
					if ($subtype == "widget") { // Special case for widgets
						$subtype = "widget/" . $object->handler;
					}
					if ($subtype == '')
						$subtype = 'default';
						
						
					$activity_view = 'activity';	
					if (!elgg_view_exists("$activity_view/$type/$subtype/$event"))
						$activity_view = 'river';
									
					$tam = elgg_view("$activity_view/$type/$subtype/$event", array(
						'statement' => $statement
					));
					
					
					// Giftwrap
					if (!empty($tam)) {
						$tam = elgg_view("activity/wrapper",array(
									'entry' => $tam,
									'time' => $log->time_created,
									'event' => $event,
									'statement' => $statement 
						));
					}
					
					$activity[] = $tam;
				}
			}
		}
		
		return $activity;
		
	}
	
	/**
	 * Export a given field of a statement as a bit of opendd metadata
	 *
	 * @param ElggRiverStatement $statement
	 * @param int $logid The id of the log entry this was generated from
	 * @param string $field The fieldname
	 * @return ODDMetadata object or false if field not available.
	 */
	function activity_export_field_from_statement(ElggRiverStatement $statement, $logid, $field)
	{
		global $CONFIG;
		
		$result = false;
		
		// Convert
		$object_return = $statement->getObject();
		if (is_array($object_return)) {
			
			$object = array();
			foreach ($object_return as $c)
				$object[] = $c;
		}
		else
			$object = $object_return;
			

		switch ($field)
		{
			case 'timestamp' : $value = $statement->getTimestamp(); break;
			case 'event' : $value = $statement->getEvent(); break;
			case 'subject' : $subject = $statement->getSubject();
							$value = guid_to_uuid($subject->guid);		
							break;				
			case 'object' : 
							if ($object instanceof ElggEntity) $value = guid_to_uuid($object->guid);
							if ((is_array($object)) && (isset($object[0]))) {
								if ( ($object[0] instanceof ElggEntity) || ($object[0] instanceof ElggExtender) || ($object[0] instanceof ElggRelationship))
									$value = get_uuid_from_object($object[0]);
								else
									$value = $object[0];
							}
							break;
			case 'object2' :
				 
				if ( (is_array($object)) && (isset($object[1]))) {
					if ( ($object[1] instanceof ElggEntity) || ($object[1] instanceof ElggExtender) || ($object[1] instanceof ElggRelationship))
						$value = get_uuid_from_object($object[1]);
					else
						$value = $object[1];
				}
				
				break;
			case 'object3' :
				 
				if ( (is_array($object)) && (isset($object[2]))) {
					if ( ($object[2] instanceof ElggEntity) || ($object[2] instanceof ElggExtender) || ($object[2] instanceof ElggRelationship))
						$value = get_uuid_from_object($object[2]);
					else
						$value = $object[2];
				}
				
				break;
			
		}
		
		//$md = new ODDMetaData("{$CONFIG->url}pg/activity/export/uuid/metadata/$id/$metaname/", "{$CONFIG->url}pg/activity/export/uuid/metadata/$id/", $metaname, $value);
		if ($value)
			$result = new ODDMetaData("{$CONFIG->url}pg/activity/export/uuid/metadata/$logid/$field/", "{$CONFIG->url}pg/activity/export/uuid/metadata/$logid/", $field, $value);

		return $result;
	}
	
	/**
	 * Export an Elgg river statement as OpenDD
	 *
	 * @param ElggRiverStatement $statement
	 * @param int $logid The id of the log entry this was generated from
	 */
	function activity_export_statement(ElggRiverStatement $statement, $logid)
	{
		global $CONFIG;
		
		$result = array();
		
		// Export activity
		$result[] = new ODDEntity("{$CONFIG->url}pg/activity/export/uuid/statement/$logid/", 'riverstatement');
		
		foreach ( 
				array(
				'timestamp', 
				'subject', 
				'object', 
				'object2', 
				'object3', 
				'event') as $field
		)
			$result[] = activity_export_field_from_statement($statement, $logid, $field);
			
		
		return $result;
	}
	
	/**
	 * Get the activity stream and output it as an opendd feed of data
	 *
	 * @param unknown_type $limit
	 * @param unknown_type $offset
	 * @param unknown_type $type
	 * @param unknown_type $subtype
	 * @param unknown_type $owner_guid
	 * @param unknown_type $owner_relationship
	 */
	function activity_get_activity_opendd($limit = 10, $offset = 0, $type = "", $subtype = "", $owner_guid = "", $owner_relationship = "" )
	{
		global $CONFIG;
		
		$log_data = activity_get_activity_data($limit, $offset, $type, $subtype, $owner_guid, $owner_relationship);

		// until count reached, loop through and render
		$activity = array();
		
		if ($log_data)
		{
			foreach ($log_data as $log)
			{
				// See if we have access to the object we're talking about
				$statement = construct_riverstatement_from_log($log);
				
				$event = $log->event;
				$class = $log->object_class;
				$type = $log->object_type;
				$subtype = $log->object_subtype;
				$tmp = new $class();
				$object = $tmp->getObjectFromID($log->object_id);	
				$by_user_obj = get_entity($log->performed_by_guid);
				
				// Belts and braces
				if ($statement)
				{
					$activity[] = activity_export_statement($statement, $log->id);
				}
			}
		}
		
		return $activity;
	}
	
	
	
	// river index with tabs to drill down
	
	
	
	
	/// BONUS POINTS
	
	// comment on feed items
	
	// comment on search terms/ tags
	
	
	// Initialise plugin
	register_elgg_event_handler('init','system','activity_init');
	register_elgg_event_handler('pagesetup','system','activity_page_setup');
?>