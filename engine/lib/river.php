<?php
	/**
	 * Elgg river.
	 * Functions for listening for and generating the river out of the system log.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
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
	 * @author Marcus Povey
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
		 * Create the statement.
		 *
		 * @param ElggUser $subject The subject (the user who created this)
		 * @param string $event The event.
		 * @param mixed $object The object, either an ElggEntity or an associated array 
		 * 	('subject' => ElggEntity, 'relationship' => relationship, 'object' => ElggEntity) or 
		 *  ('subject' => ElggEntity, 'object' => ElggEntity)
		 */
		public function __construct(ElggUser $subject, $event, $object)
		{
			$this->setSubject($subject);
			$this->setEvent($event);
			$this->setObject($object);
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
	 * \TODO: Make this more efficient / reduce DB queries.
	 * 
	 * @param int $by_user The user who initiated the event.
	 * @param string $relationship Limit return results to only those users who $by_user has $relationship with.
	 * @param int $limit Maximum number of events to show
	 * @param int $offset An offset
	 * @return array of river entities rendered with the appropriate view.
	 */
	function get_river_entries($by_user = "", $relationship = "", $limit = 10, $offset = 0)
	{
		// set start limit and offset
		$cnt = $limit; // Didn' cast to int here deliberately
		$off = $offset; // here too
		
		if (is_array($by_user) && sizeof($by_user) > 0) {
			foreach($by_user as $key => $val) {
				$by_user[$key] = (int) $val;
			}
		} else {
			$by_user = (int)$by_user;
		}
		
		$exit = false;
		
		// River objects
		$river = array();
	
		do
		{
			$log_events = get_system_log($by_user, "","", $cnt, $off);
		
			if (!$log_events)
				$exit = true;
			else
			{
			
				foreach ($log_events as $log)
				{
					// See if we have access to the object we're talking about
					$event = $log->event;
					$class = $log->object_class;
					$tmp = new $class();
					$object = $tmp->getObjectFromID($log->object_id);
					
					// Exists and we have access to it
					// if (is_a($object, $class))
					if ($object instanceof $class)
					{
						// If no relationship defined or it matches $relationship
						if (
							(!$relationship) || 
							(
								($relationship) &&
								(check_entity_relationship($by_user, $relationship, $tmp->getObjectOwnerGUID()))
							)
						)
						{
							// See if anything can handle it
							$tam = "";
							
							// Construct the statement
							$by_user_obj = get_entity($log->performed_by_guid);
							$statement_object = $object;
							if ($object instanceof ElggRelationship) {
								
								$statement_object = array(
									'subject' => get_entity($object->guid_one),
									'relationship' => $object->relationship,
									'object' => get_entity($object->guid_two) 
								);
							} else if ($object instanceof ElggExtender) {
								$statement_object = array(
									'subject' => $object,
									'object' => get_entity($object->entity_guid)  
								);
							}
							$statement = new ElggRiverStatement($by_user_obj, $event, $statement_object);
							
							
							if ($object instanceof ElggEntity) {
								$subtype = $object->getSubtype();
							} else {
								$subtype = "";
							}
							if ($subtype == "widget") {
								$subtype = "widget/" . $object->handler;
							}
							
							if (!empty($subtype) && elgg_view_exists("river/{$subtype}/{$event}")) {
								$tam = elgg_view("river/{$subtype}/$event", array(
									'statement' => $statement
								));
							} else if (elgg_view_exists("river/$class/$event")) {
								$tam = elgg_view("river/$class/$event", array(
									'statement' => $statement
								));
							}
							
							if (!empty($tam)) {
								$tam = elgg_view("river/wrapper",array(
											'entry' => $tam,
											'time' => $log->time_created,
											'event' => $event,
											'statement' => $statement 
								));
							}
							
							if ($tam)
							{
								$river[] = $tam;
								$cnt--;
							}
						}
					}
					
					// Increase offset
					$off++;
				}
			}
						
		} while (
			($cnt > 0) &&
			(!$exit)
		);
		
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
		// set start limit and offset
		$cnt = $limit; // Didn' cast to int here deliberately
		$off = $offset; // here too
		
		if (is_array($by_user) && sizeof($by_user) > 0) {
			foreach($by_user as $key => $val) {
				$by_user[$key] = (int) $val;
			}
		} else {
			$by_user = (int)$by_user;
		}
		
		$exit = false;
		
		// River objects
		$river = new ODDDocument();
	
		do
		{
			$log_events = get_system_log($by_user, "","", $cnt, $off);
		
			if (!$log_events)
				$exit = true;
			else
			{
			
				foreach ($log_events as $log)
				{
					// See if we have access to the object we're talking about
					$event = $log->event;
					$class = $log->object_class;
					$tmp = new $class();
					$object = $tmp->getObjectFromID($log->object_id);
					
					// Exists and we have access to it
					// if (is_a($object, $class))
					if ($object instanceof $class)
					{
						// If no relationship defined or it matches $relationship
						if (
							(!$relationship) || 
							(
								($relationship) &&
								(check_entity_relationship($by_user, $relationship, $tmp->getObjectOwnerGUID()))
							)
						)
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
								//$relationship_obj = $object->export(); // I figure this is what you're actually interested in in this instance.
							}
							
							// If we have handled it then add it to the document
							if ($relationship_obj) {
								$relationship_obj->setPublished($log->time_created); 
								$river->addElement($relationship_obj);
							}
							
						}
					}
					
					// Increase offset
					$off++;
				}
			}
						
		} while (
			($cnt > 0) &&
			(!$exit)
		);
		
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