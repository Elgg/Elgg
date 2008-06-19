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
		$by_user = (int)$by_user;
		if ($by_user)
			$by_user_obj = get_entity($by_user);
		
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
							
							// test if view exist and if so
							if (isset($by_user_obj) && $by_user_obj instanceof ElggUser) {
							} else {
								$by_user_obj = get_entity($log->performed_by_guid);
							}
							$tam = elgg_view("river/$class/$event", array(
								'performed_by' => $by_user_obj,
								'log_entry' => $log,
								'object' => $object
							));
							
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
		return elgg_view($view, array('river' => get_river_entries($guid,"friend", $limit, $offset)));
	}
?>