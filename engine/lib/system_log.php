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
	}
	
	/**
	 * Retrieve the system log based on a number of parameters.
	 * 
	 * @param string $event The event you are searching on.
	 * @param string $class The class of object it effects.
	 * @param int $limit
	 * @param int $offset
	 */
	function get_system_log($event = "", $class = "", $limit = 10, $offset = 0)
	{
		global $CONFIG;
		
		$event = sanitise_string($event);
		$class = sanitise_string($class);
		$limit = (int)$limit;
		$offset = (int)$offset;
		
		$access = get_access_list();
		
		$where = array();
		
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
	 * @param $object The object you're talking about.
	 * @param $event String The event being logged
	 */
	function system_log($object, $event)
	{
		global $CONFIG;
		error_log("***************************** EVENT: $event : ".print_r($object,true));
		if ($object instanceof Loggable)
		{
			// Has loggable interface, extract the necessary information and store
			$object_id = (int)$object->getSystemLogID();
			$object_class = $object->getClassName();
			$event = sanitise_string($event);
			$time = time();
			
			// Create log
			return insert_data("INSERT into {$CONFIG->dbprefix}system_log (object_id, object_class, event, time_created) VALUES ('$object_id','$object_class','$event', '$time')");
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
	register_event_handler('all','all','system_log_listener');
	
?>