<?php
	/**
	 * Notifications
	 * This file contains classes and functions which allow plugins to register and send notifications.
	 * 
	 * There are notification methods which are provided out of the box (see notification_init() ). Each method
	 * is identified by a string, e.g. "email".
	 * 
	 * To register an event use register_notification_handler() and pass the method name and a handler function.
	 * 
	 * To send a notification call notify() passing it the method you wish to use combined with a number of method 
	 * specific addressing parameters.
	 * 
	 * Catch NotificationException to trap errors.
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
	 * @param mixed $method The method used as a string, or an array if multiple methods should be used. 
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
			
		if (!$method)
			throw new NotificationException(elgg_echo('NotificationException:NoNotificationMethod'));
			
		if (!is_array($method))
			$method = array($method);
			
		if ((!array_key_exists($method, $NOTIFICATION_HANDLERS)) || (!is_callable($NOTIFICATION_HANDLERS[$method])))
			throw new NotificationExceptions(sprintf(elgg_echo('NotificationException:NoHandlerFound'), $method));
			
		if (!is_array($to))
			$to = array($to);
			
		foreach ($to as $guid)
		{	
			foreach ($method as $m)
				if (!$NOTIFICATION_HANDLERS[$m]((int)$guid, sanitise_string($m), $params))
					throw new NotificationException(sprintf(elgg_echo('NotificationException:ErrorNotifyingGuid'), $guid));
		}
			
		return true;
	}
	
	/**
	 * Get the notification settings for a given user.
	 *
	 * @param int $user_guid The user id
	 * @return stdClass 
	 */
	function get_user_notification_settings($user_guid = 0)
	{
		$user_guid = (int)$user_guid;
		
		if ($user_guid == 0) 
			$user_guid = $_SESSION['user']->guid;
		
		$all_metadata = get_metadata_for_entity($user_guid);
		if ($all_metadata)
		{
			$prefix = "notification:method:";
			$return = new stdClass;
			
			foreach ($all_metadata as $meta)
			{
				$name = substr($meta->name, strlen($prefix));
				$value = $meta->value;
				
				if (strpos($meta->name, $prefix) === 0)
					$return->$name = $value;
			}

			return $return;			
		}
		
		return false;
	}
	
	/**
	 * Set a user notification pref.
	 *
	 * @param int $user_guid The user id.
	 * @param string $method The delivery method (eg. email)
	 * @param bool $value On(true) or off(false).
	 * @return bool
	 */
	function set_user_notification_setting($user_guid, $method, $value)
	{
		$user_guid = (int)$user_guid;
		$method = sanitise_string($method);

		if ($user_guid == 0) 
			$user_guid = $_SESSION['user']->guid;
			
		$user = get_entity($user_guid);
		
		if (($user) && ($user instanceof ElggUser))
		{			
			$prefix = "notification:method:$method";
			$user->$prefix = $value;
			$user->save();
		
			return true;
		}
			
		return false;
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
			throw new NotificationException(sprintf(elgg_echo('NotificationException:NoEmailAddress'), $to_guid));
		
		
		$to = $entity->email;
		$subject = $params['subject'];
		$from = $params['from'];
		
		$headers = "From: $from\r\n";
				
		return mail($to, $subject, $message, $headers);
	}

	/**
	 * Correctly initialise notifications and register the email handler.
	 *
	 */
	function notification_init()
	{
		// Register a notification handler for the default email method
		register_notification_handler("email", "email_notify_handler");
		
		// Add settings view to user settings & register action
		extend_elgg_settings_page('notifications/settings/usersettings', 'usersettings/user');
		register_action("notifications/settings/usersettings/save");
		
	}

	// Register a startup event
	register_elgg_event_handler('init','system','notification_init',0);	
?>