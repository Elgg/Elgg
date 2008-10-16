<?php
	/**
	 * Elgg activity plugin.
	 * 
	 * @package ElggActivity
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
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
		
		// Activity main menu
		if (isloggedin())
		{
			add_menu(elgg_echo('activity:your'), $CONFIG->wwwroot . "pg/activity/{$_SESSION['user']->username}/", array(), 'activity');
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
		
		if ($page[0])
			set_input('username', $page[0]);

		if ($page[0]) {
			if ($page[1])
			{
				switch ($page[1])
				{
					case 'friends' :
						include($CONFIG->pluginspath . "activity/friends.php");
					break;
				}
			}
			else
				include($CONFIG->pluginspath . "activity/index.php");	
		} else
			include($CONFIG->pluginspath . "activity/all.php");
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
		$activity_views = array_merge(elgg_view_tree('activity'), elgg_view_tree('river')); // Join activity with river

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
		if (count($owner_guid) && ($owner_guid[0]!=0))
			$user = " and sl.performed_by_guid in (".implode(',', $owner_guid).")";
		
		// Relationship
		$relationship_query = "";
		$relationship_join = "";
		if ($relationship)
		{
			$relationship_join = " join {$CONFIG->dbprefix}entity_relationships r on sl.performed_by_guid=r.entity_guid ";
			$relationship_query = "r.relationship = '$relationship'";
		}
		
		$query = "SELECT sl.* from {$CONFIG->dbprefix}system_log sl $relationship_join where 1 $user and $relationship_query ($obj_query) order by sl.time_created desc  limit $offset, $limit";
		$log_data = get_data($query);

		// until count reached, loop through and render
		$activity = array();
		
		if ($log_data)
		{
			foreach ($log_data as $log)
			{
				// See if we have access to the object we're talking about
				$event = $log->event;
				$class = $log->object_class;
				$type = $log->object_type;
				$subtype = $log->object_subtype;
				$tmp = new $class();
				$object = $tmp->getObjectFromID($log->object_id);	
				$by_user_obj = get_entity($log->performed_by_guid);
				
				// Belts and braces
				if ($object instanceof $class)
				{
					$tam = "";
					
					// Construct the statement
					$statement_object = $object; // Simple object, we don't need to do more
							
					// This is a relationship, slighty more complicated
					if ($object instanceof ElggRelationship) {
								
						$statement_object = array(
							'subject' => get_entity($object->guid_one),
							'relationship' => $object->relationship,// Didn' cast to int here deliberately
							'object' => get_entity($object->guid_two) 
						);
						
					// Metadata or annotations, also slightly more complicated
					} else if ($object instanceof ElggExtender) {
						$statement_object = array(
							'subject' => $object,
							'object' => get_entity($object->entity_guid)  
						);
					}

					// Put together a river statement
					$statement = new ElggRiverStatement($by_user_obj, $event, $statement_object);
					
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
	
	// river index with tabs to drill down
	
	
	
	
	/// BONUS POINTS
	
	// comment on feed items
	
	// comment on search terms/ tags
	
	
	// Initialise plugin
	register_elgg_event_handler('init','system','activity_init');
	register_elgg_event_handler('pagesetup','system','activity_page_setup');
?>