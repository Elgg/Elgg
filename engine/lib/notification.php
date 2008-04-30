<?php
	/**
	 * Notifications
	 * This file contains classes and functions which allow plugins to register and send notifications.
	 * 
	 * @package Elgg
	 * @subpackage API
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/** Notification handlers */
	$NOTIFICATION_HANDLERS = array();
	
	/**
	 * This function registers a handler for a given notification type (eg "email")
	 *
	 * @param string $method The method
	 * @param string $handler The handler function, in the format "handler($to_guid, $message, array $params = NULL)"
	 */
	function register_notification_handler($method, $handler)
	{
		global $NOTIFICATION_HANDLERS;
		
		if (is_callable($handler)) 
		{
			$NOTIFICATION_HANDLERS[$method] = $handler;
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * Send a notification message using a pre-registered method.
	 *
	 * @param string $method The method used 
	 * @param mixed $to Either a guid or an array of guid's to notify
	 * @param string $message A message
	 * @param array $params Optional method specific parameters as an associative array, for example "subject", "from"
	 * @return boolean
	 * @throws NotificationException
	 */
	function notify($method, $to, $message, array $params = NULL)
	{
		global $NOTIFICATION_HANDLERS;
		
		// Sanitise
		if (!is_array($to))
			$to = (int)$to;
			
		if ($method=="")
			throw new NotificationException("No notification method specified.");
			
		if ((!array_key_exists($method, $NOTIFICATION_HANDLERS)) || (!is_callable($NOTIFICATION_HANDLERS[$method])))
			throw new NotificationExceptions("No handler found for '$method' or it was not callable.");
			
		if (!is_array($to))
			$to = array($to);
			
		foreach ($to as $guid)	
				if (!$NOTIFICATION_HANDLERS[$method]($guid, sanitise_string($method), $params))
					throw new NotificationException("There was an error while notifying $guid");
			
		return true;
	}
	
	/**
	 * Notification exception.
	 * @author Marcus Povey
	 */
	class NotificationException extends Exception {}


	/**
	 * Send a notification via email.
	 * 
	 * Parameters accept "from" and "subject" as values.
	 */
	function email_notify_handler($to_guid, $message, array $params = NULL)
	{
		$to_guid = (int)$to_guid;
		
		$entity = get_entity($to_guid);
		
		if ((!($entity instanceof ElggUser)) || ($entity->email==""))
			throw new NotificationException("Could not get the email address for GUID:$to_guid");
		
		
		$to = $entity->email;
		$subject = $params['subject'];
		$from = $params['from'];
		
		$headers = "From: $from\r\n";
				
		return mail($to, $subject, $message, $headers);
	}
?>