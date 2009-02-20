<?php
	/**
	 * Elgg river.
	 * Functions for listening for and generating the river out of the system log.
	 * 
	 * These functions are no longer used in core Elgg.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	/**
	 * @class ElggRiverComponent Component passed to river views.
	 * This class represents all the necessary information for constructing a river article - this includes:
	 *  - The user who performed the action
	 *  - The object the action was performed on
	 *  - The event performed
	 *  - Any related objects
	 * 
	 * @author Curverider Ltd
	 */
	class ElggRiverStatement
	{
		/**
		 * Object in question (may be a relationship event or a metadata event). In the case of a relationship this is an array containing 
		 * the objects that the relationship is established between. in the case of metadata it consists of 
		 */
		private $object;
		
		/**
		 * The log event (create / update etc).
		 */
		private $log_event; 
		
		/**
		 * The subject who created this event (the user).
		 */
		private $subject;
		
		/**
		 * When the event occured.
		 */
		private $timestamp;
		
		/**
		 * Create the statement.
		 *
		 * @param ElggUser $subject The subject (the user who created this)
		 * @param string $event The event.
		 * @param mixed $object The object, either an ElggEntity or an associated array 
		 * 	('subject' => ElggEntity, 'relationship' => relationship, 'object' => ElggEntity) or 
		 *  ('subject' => ElggEntity, 'object' => ElggEntity)
		 * @param int $timestamp The timestamp
		 */
		public function __construct(ElggUser $subject, $event, $object, $timestamp)
		{
			$this->setSubject($subject);
			$this->setEvent($event);
			$this->setObject($object);
			$this->setTimestamp($timestamp);
		}
		
		/**
		 * Set the subject.
		 *
		 * @param ElggEntity $subject The subject.
		 */
		public function setSubject(ElggEntity $subject) { $this->subject = $subject; }
		
		/**
		 * Return the user that created this event - the subject of the statement.
		 * @return ElggUser
		 */
		public function getSubject() { return $this->subject; }
		
		/**
		 * Return the user who initiated this event (an alias of getSubject();
		 * @return ElggUser
		 */
		public function getByUser() { return $this->getSubject(); }
		
		/**
		 * Set the object.
		 *
		 * @param mixed $object ElggEntity or array.
		 * @return bool
		 */
		public function setObject($object)
		{
			if (is_array($object))
			{
				/*if (
					(!isset($object['subject'])) ||
					(
						(!($object['subject'] instanceof ElggEntity)) ||
						(!($object['subject'] instanceof ElggExtender))
					)
				)
					return false;
				
				if ( (!isset($object['object'])) || (!($object['object'] instanceof ElggEntity)) )
					return false;
					*/
				$this->object = $object;
				
				return true;
			}
			else if ($object instanceof ElggEntity)
			{
				$this->object = $object;
				
				return true;
			}
			
			return false;
		}
		
		/**
		 * Return the accusitive object of the statement. This is either an object in isolation, or an array containing
		 * the parts of the statement.
		 * 
		 * E.g. 
		 * 
		 * 	For the statement "User X created object Y", this function will return object Y.
		 *  
		 * 	However, for a statement "User X is now friends with User Y" you are essentially making the system level statement
		 *  "User X has created a relationship of type friend between Y and Z" (where X is almost always going to be the same as Y).. therefore
		 *  this function will return a three element array associative containing the relationship type, plus the elements the relationship 
		 *  is between ['subject', 'relationship', 'object'].
		 * 
		 *  Also, if you are updating a bit of metadata about an object this a two element array: ['subject', 'object'].
		 *  Which is making the statement "User X updated some Metadata (subject) about object (object) Y
		 * 
		 * @return mixed
		 */
		public function getObject() { return $this->object; }
		
		/**
		 * Set the log event.
		 *
		 * @param string $event The event - e.g. "update".
		 */
		public function setEvent($event) { $this->log_event = $event; }
		
		/**
		 * Return the event in the system log that this action relates to (eg, "create", "update").
		 * @return string
		 */
		public function getEvent() { return $this->log_event; }
		
		/**
		 * Set when this event occured.
		 *
		 * @param int $timestamp Unix TS
		 */
		public function setTimestamp($timestamp) { $this->timestamp = $timestamp; }
		
		/**
		 * Retrieve when this event occured.
		 *
		 * @return int Unix TS
		 */
		public function getTimestamp() { return $this->timestamp; }
	}

	/**
	 * Perform a somewhat complicated query to extract river data from the system log based on available views.
	 * 
	 * NOTE: Do not use this function directly. It is called elsewhere and is subject to change without warning.
	 *
	 * @param unknown_type $by_user
	 * @param unknown_type $relationship
	 * @param unknown_type $limit
	 * @param unknown_type $offset
	 * @return unknown
	 */
	function __get_river_from_log($by_user = "", $relationship = "", $limit = 10, $offset = 0)
	{
		global $CONFIG;
	
		// Get all potential river events from available view
		$river_events = array(); 
		$river_views = elgg_view_tree('river');
		foreach ($river_views as $view)
		{
			$fragments = explode('/', $view);

			if ((isset($fragments[0])) && ($fragments[0] == 'river'))
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
					$river_events[] = $f; 
					
				}
			}
		}
		// Construct query
		
		// Objects
		$n = 0;
		foreach ($river_events as $details)
		{
			// Get what we're talking about
		
			if ((isset($details['subtype'])) && ($details['subtype'] == 'default')) $details['subtype'] = '';
			
			if ((isset($details['type'])) && (isset($details['event']))) {
				if ($n>0) $obj_query .= " or ";
				
				$access = "";
				if ($details['type']!='relationship')
					$access = " and " . get_access_sql_suffix('sl');
				 
				$obj_query .= "( sl.object_type='{$details['type']}' and sl.object_subtype='{$details['subtype']}' and sl.event='{$details['event']}' $access )";
				
				$n++;
			}
		
		}		
	
		// User
		$user = "sl.performed_by_guid in (".implode(',', $by_user).")";
		
		// Relationship
		$relationship_query = "";
		$relationship_join = "";
		if ($relationship)
		{
			$relationship_join = " join {$CONFIG->dbprefix}entity_relationships r on sl.performed_by_guid=r.entity_guid ";
			$relationship_query = "r.relationship = '$relationship'";
		}
		
		$query = "SELECT sl.* from {$CONFIG->dbprefix}system_log sl $relationship_join where $user and $relationship_query ($obj_query) order by sl.time_created desc  limit $offset, $limit";

		// fetch data from system log (needs optimisation)
		return get_data($query);
	}
	
	/**
	 * Construct a river statement out of an entry in the system log.
	 *
	 * @param stdClass $log_entry
	 * @return mixed Either an ElggRiverStatement or false
	 */
	function construct_riverstatement_from_log($log_entry)
	{
		if (!($log_entry instanceof stdClass)) return false;
		
		$log = $log_entry;
		
		// See if we have access to the object we're talking about
		$event = $log->event;
		$class = $log->object_class;
		$type = $log->object_type;
		$subtype = $log->object_subtype;
		$tmp = new $class();
		$object = $tmp->getObjectFromID($log->object_id);	
		$by_user_obj = get_entity($log->performed_by_guid);
				
		if ( ($object) && ($object instanceof $class) && ($by_user_obj))
		{
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
			return new ElggRiverStatement($by_user_obj, $event, $statement_object, $log->time_created);
		}
		
		return false;
	}
	
	/**
	 * Extract a list of river events from the current system log.
	 * This function retrieves the objects from the system log and will attempt to render 
	 * the view "river/CLASSNAME/EVENT" where CLASSNAME is the class of the object the system event is referring to,
	 * and EVENT is the event (create, update, delete etc).
	 * 
	 * This view will be passed the log entry (as 'log_entry') and the object (as 'object') which will be accessable 
	 * through the $vars[] array.
	 * 
	 * It returns an array of the result of each of these views.
	 * 
	 * \TODO: Limit to just one user or just one user's friends
	 * 
	 * @param int $by_user The user who initiated the event.
	 * @param string $relationship Limit return results to only those users who $by_user has $relationship with.
	 * @param int $limit Maximum number of events to show
	 * @param int $offset An offset
	 * @return array of river entities rendered with the appropriate view.
	 */
	function get_river_entries($by_user = "", $relationship = "", $limit = 10, $offset = 0)
	{
		global $CONFIG;
		
		$limit = (int)$limit;
		$offset = (int)$offset;
		$relationship = sanitise_string($relationship);
		
		if (is_array($by_user) && sizeof($by_user) > 0) {
			foreach($by_user as $key => $val) {
				$by_user[$key] = (int) $val;
			}
		} else {
			$by_user = array((int)$by_user);
		}
		
		// Get river data
		$log_data = __get_river_from_log($by_user, $relationship, $limit, $offset);
		
		// until count reached, loop through and render
		$river = array();
		
		if ($log_data)
		{
			foreach ($log_data as $log)
			{
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
						
					$tam = elgg_view("river/$type/$subtype/$event", array(
						'statement' => $statement
					));
					
					
					// Giftwrap
					if (!empty($tam)) {
						$tam = elgg_view("river/wrapper",array(
									'entry' => $tam,
									'time' => $log->time_created,
									'event' => $event,
									'statement' => $statement 
						));
					}
					
					$river[] = $tam;
				}
			}
		}
		
		return $river;
		
	}
	
	/**
	 * Extract entities from the system log and produce them as an OpenDD stream.
	 * This stream can be subscribed to and reconstructed on another system as an activity stream.
	 *
	 * @param int $by_user The user who initiated the event.
	 * @param string $relationship Limit return results to only those users who $by_user has $relationship with.
	 * @param int $limit Maximum number of events to show
	 * @param int $offset An offset
	 * @return ODDDocument
	 */
	function get_river_entries_as_opendd($by_user = "", $relationship = "", $limit = 10, $offset = 0)
	{
		global $CONFIG;
		
		$limit = (int)$limit;
		$offset = (int)$offset;
		$relationship = sanitise_string($relationship);
		
		if (is_array($by_user) && sizeof($by_user) > 0) {
			foreach($by_user as $key => $val) {
				$by_user[$key] = (int) $val;
			}
		} else {
			$by_user = array((int)$by_user);
		}
		
		// Get river data
		$log_data = __get_river_from_log($by_user, $relationship, $limit, $offset);
		
		// River objects
		$river = new ODDDocument();	
		if ($log_data)
		{
			foreach ($log_data as $log)
			{		
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
					$relationship_obj = NULL;
							
					// Handle updates of entities
					if ($object instanceof ElggEntity)
					{
						$relationship_obj = new ODDRelationship(
							guid_to_uuid($log->performed_by_guid),
							$log->event,
							guid_to_uuid($log->object_id)
						);
					}
							
					// Handle updates of metadata
					if ($object instanceof ElggExtender)
					{
						$odd = $object->export();
						$relationship_obj = new ODDRelationship(
							guid_to_uuid($log->performed_by_guid),
							$log->event,
							$odd->getAttribute('uuid')
						);
					}
							
					// Handle updates of relationships
					if ($object instanceof ElggRelationship)
					{
						$odd = $object->export();
						$relationship_obj = new ODDRelationship(
							guid_to_uuid($log->performed_by_guid),
							$log->event,
							$odd->getAttribute('uuid')
						);
					}
							
					// If we have handled it then add it to the document
					if ($relationship_obj) {
						$relationship_obj->setPublished($log->time_created); 
						$river->addElement($relationship_obj);
					}
				}
			}
			
		}		
		return $river;
		
	}
	
	/**
	 * Extract a list of river events from the current system log, from a given user's friends.
	 *
	 * @seeget_river_entries 
	 * @param int $by_user The user whose friends we're checking for.
	 * @param int $limit Maximum number of events to show
	 * @param int $offset An offset
	 * @return array of river entities rendered with the appropriate view.
	 */
	function get_river_entries_friends($by_user, $limit = 10, $offset = 0) {
		$friendsarray = "";
		if ($friends = get_user_friends($by_user, "", 9999)) {
			$friendsarray = array();
			foreach($friends as $friend) {
				$friendsarray[] = $friend->getGUID();
			}
		}
		
		return get_river_entries($friendsarray,"",$limit,$offset);
	}

	/**
	 * Simplify drawing a river for a given user.
	 *
	 * @param int $guid The user
	 * @param unknown_type $limit Limit
	 * @param unknown_type $offset Offset
	 * @param string $view Optional view to use to display the river (dashboard is assumed)
	 */
	function elgg_view_river($guid, $limit = 10, $offset = 0, $view = 'river/dashboard')
	{
		return elgg_view($view, array('river' => get_river_entries($guid,"", $limit, $offset)));
	}
	
	/**
	 * Simplify drawing a river for a given user, showing their friend's activity
	 *
	 * @param int $guid The user
	 * @param unknown_type $limit Limit
	 * @param unknown_type $offset Offset
	 * @param string $view Optional view to use to display the river (dashboard is assumed)
	 */
	function elgg_view_friend_river($guid, $limit = 10, $offset = 0, $view = 'river/dashboard')
	{
		return elgg_view($view, array('river' => get_river_entries_friends($guid, $limit, $offset)));
	}
?>