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
		 * Return the class name of the object. Added as a function because get_class causes errors for some reason.
		 */
		public function getClassName();
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
		
		if ($object instanceof Loggable)
		{
			// Has loggable interface, extract the necessary information and store
			$object_id = (int)$object->getSystemLogID();
			$object_class = $object->getClassName();
			$event = sanitise_string($event);
			$time = time();
			
			// Create log
			return insert_data("INSERT into {$CONFIG->dbprefix}system_log (object_id, object_class, event, time_created) VALUES ('$object_id','$object_class','$event','$time')");
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