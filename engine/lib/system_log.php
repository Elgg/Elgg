<?php
	/**
	 * Elgg system log.
	 * Listens to events and writes crud events into the system log database.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * Interface that provides an interface which must be implemented by all objects wishing to be 
	 * recorded in the system log (and by extension the river).
	 * 
	 * This interface defines a set of methods that permit the system log functions to hook in and retrieve
	 * the necessary information and to identify what events can actually be logged.
	 * 
	 * To have events involving your object to be logged simply implement this interface.
	 * 
	 * @author Marcus Povey
	 */
	interface Loggable 
	{
		/**
		 * Return an identification for the object for storage in the system log. 
		 * This id must be an integer.
		 * 
		 * @return int 
		 */
		public function getSystemLogID();
		
		/**
		 * Return the class name of the object. 
		 * Added as a function because get_class causes errors for some reason.
		 */
		public function getClassName();
		
		/**
		 * For a given ID, return the object associated with it.
		 * This is used by the river functionality primarily.
		 * This is useful for checking access permissions etc on objects.
		 */
		public function getObjectFromID($id);
		
		/**
		 * Return the GUID of the owner of this object.
		 */
		public function getObjectOwnerGUID();
	}
	
	/**
	 * Retrieve the system log based on a number of parameters.
	 * 
	 * @param int $by_user The user who initiated the event.
	 * @param string $event The event you are searching on.
	 * @param string $class The class of object it effects.
	 * @param int $limit Maximum number of responses to return.
	 * @param int $offset Offset of where to start.
	 */
	function get_system_log($by_user = "", $event = "", $class = "", $limit = 10, $offset = 0)
	{
		global $CONFIG;
		
		if (is_array($by_user) && sizeof($by_user) > 0) {
			foreach($by_user as $key => $val) {
				$by_user[$key] = (int) $val;
			}
		} else {
			$by_user = (int)$by_user;
		}
		$event = sanitise_string($event);
		$class = sanitise_string($class);
		$limit = (int)$limit;
		$offset = (int)$offset;
		
		$where = array();
		
		if (is_int($by_user) && $by_user > 0) {
			$where[] = "performed_by_guid=$by_user";
		} else if (is_array($by_user)) {
			$where [] = "performed_by_guid in (". implode(",",$by_user) .")";
		}
		if ($event != "")
			$where[] = "event='$event'";
		if ($class!=="")
			$where[] = "object_class='$class'";
			
		$query = "SELECT * from {$CONFIG->dbprefix}system_log where 1 ";
		foreach ($where as $w)
			$query .= " and $w";
		
		$query .= " order by time_created desc";
		$query .= " limit $offset, $limit"; // Add order and limit
		
		return get_data($query);
	}
	
	/**
	 * Log a system event related to a specific object.
	 * 
	 * This is called by the event system and should not be called directly.
	 * 
	 * @param $object The object you're talking about.
	 * @param $event String The event being logged
	 */
	function system_log($object, $event)
	{
		global $CONFIG;
		static $logcache;

		if ($object instanceof Loggable)
		{
			
			if (!is_array($logcache)) $logcache = array();
			
			// Has loggable interface, extract the necessary information and store
			$object_id = (int)$object->getSystemLogID();
			$object_class = $object->getClassName();
			$event = sanitise_string($event);
			$time = time();
			$performed_by = (int)$_SESSION['guid'];
			
			// Create log if we haven't already created it
			if (!isset($logcache[$time][$object_id][$event])) {
				if (insert_data("INSERT into {$CONFIG->dbprefix}system_log (object_id, object_class, event, performed_by_guid, time_created) VALUES ('$object_id','$object_class','$event',$performed_by, '$time')")) {
					$logcache[$time][$object_id][$event] = true;
					return true;
				}
				return false;
			}
			
			return true;
			
		}
	}
	
	/**
	 * System log listener.
	 * This function listens to all events in the system and logs anything appropriate.
	 *
	 * @param String $event
	 * @param String $object_type
	 * @param mixed $object
	 */
	function system_log_listener($event, $object_type, $object)
	{
		system_log($object, $event);
		
		return true;
	}
	
	/** Register event to listen to all events **/
	register_elgg_event_handler('all','all','system_log_listener', 400);
	
?>