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
	 * @param int $limit
	 * @param int $offset
	 * @return array
	 */
	function get_river_entries($limit = 10, $offset = 0)
	{
		// set start limit and offset
		$cnt = $limit;
		$off = $offset;
		
		$exit = false;
		
		// River objects
		$river = array();
		
		do
		{
			$log_events = get_system_log("","", $cnt, $off);
			
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
					if (is_a($object, $class))
					{
						// See if anything can handle it
						$tam = "";
						
						// test if view exist and if so
						$tam = elgg_view("river/$class/$event", array(
							'performed_by' => get_entity($log->performed_by_guid),
							'log_entry' => $log,
							'object' => $object
						));
						
						if ($tam)
						{
							$river[] = $tam;
							$cnt--;
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

?>