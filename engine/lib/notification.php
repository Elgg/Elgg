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
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/** Notification handlers */
	$NOTIFICATION_HANDLERS = array();
	
	/**
	 * This function registers a handler for a given notification type (eg "email")
	 *
	 * @param string $method The method
	 * @param string $handler The handler function, in the format "handler(ElggEntity $from, ElggUser $to, $subject, $message, array $params = NULL)". This function should return false on failure, and true/a tracking message ID on success.
	 * @param array $params A associated array of other parameters for this handler defining some properties eg. supported message length or rich text support.
	 */
	function register_notification_handler($method, $handler, $params = NULL)
	{
		global $NOTIFICATION_HANDLERS;
		
		if (is_callable($handler)) 
		{
			$NOTIFICATION_HANDLERS[$method] = new stdClass;

			$NOTIFICATION_HANDLERS[$method]->handler = $handler;
			if ($params)
			{
				foreach ($params as $k => $v)
					$NOTIFICATION_HANDLERS[$method]->$k = $v;
			}
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * Notify a user via their preferences.
	 *
	 * @param mixed $to Either a guid or an array of guid's to notify.
	 * @param int $from GUID of the sender, which may be a user, site or object.
	 * @param string $subject Message subject.
	 * @param string $message Message body.
	 * @param array $params Misc additional parameters specific to various methods.
	 * @param mixed $methods_override A string, or an array of strings specifying the delivery methods to use - or leave blank
	 * 				for delivery using the user's chosen delivery methods.
	 * @return array Compound array of each delivery user/delivery method's success or failure.
	 * @throws NotificationException
	 */
	function notify_user($to, $from, $subject, $message, array $params = NULL, $methods_override = "")
	{
		global $NOTIFICATION_HANDLERS, $CONFIG;
	
		// Sanitise
		if (!is_array($to))
			$to = array((int)$to);
		$from = (int)$from;
		//$subject = sanitise_string($subject);
			
		// Get notification methods
		if (($methods_override) && (!is_array($methods_override)))
			$methods_override = array($methods_override);
			
		$result = array();
		
		foreach ($to as $guid)
		{
			// Results for a user are...
			$result[$guid] = array();
			
			if ($guid) { // Is the guid > 0? 
				// Are we overriding delivery?
				$methods = $methods_override;
				if (!$methods)
				{
					$tmp = (array)get_user_notification_settings($guid);
					$methods = array(); 
					foreach($tmp as $k => $v)
						if ($v) $methods[] = $k; // Add method if method is turned on for user!
				}
				
				if ($methods)
				{
					// Deliver
					foreach ($methods as $method)
					{
						// Extract method details from list
						$details = $NOTIFICATION_HANDLERS[$method];
						$handler = $details->handler;
					
						if ((!$NOTIFICATION_HANDLERS[$method]) || (!$handler))
							throw new NotificationException(sprintf(elgg_echo('NotificationException:NoHandlerFound'), $method));
		
						if ($CONFIG->debug)
							error_log("Sending message to $guid using $method");					
							
						// Trigger handler and retrieve result.
						$result[$guid][$method] = $handler(
							$from ? get_entity($from) : NULL, 	// From entity
							get_entity($guid), 					// To entity
							$subject,							// The subject
							$message, 			// Message
							$params								// Params
						);
						
					}
				}
			}		
		}
	
		return $result;
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
		
		if ($user_guid == 0) $user_guid = get_loggedin_userid();
		
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
			
		$user = get_entity($user_guid);
		if (!$user) $user = get_loggedin_user();
		
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
	 * @author Curverider Ltd
	 */
	class NotificationException extends Exception {}

	
	/**
	 * Send a notification via email.
	 *
	 * @param ElggEntity $from The from user/site/object
	 * @param ElggUser $to To which user?
	 * @param string $subject The subject of the message.
	 * @param string $message The message body
	 * @param array $params Optional parameters (none taken in this instance)
	 * @return bool
	 */
	function email_notify_handler(ElggEntity $from, ElggUser $to, $subject, $message, array $params = NULL)
	{
		global $CONFIG;
		
		if (!$from)
			throw new NotificationException(sprintf(elgg_echo('NotificationException:MissingParameter'), 'from'));
			 
		if (!$to)
			throw new NotificationException(sprintf(elgg_echo('NotificationException:MissingParameter'), 'to'));
		
		if ($to->email=="")
			throw new NotificationException(sprintf(elgg_echo('NotificationException:NoEmailAddress'), $to->guid));			

		// Sanitise subject
		$subject = preg_replace("/(\r\n|\r|\n)/", " ", $subject); // Strip line endings
			
		// To 
		$to = $to->email;
		
		// From
		$site = get_entity($CONFIG->site_guid);
		if ((isset($from->email)) && (!($from instanceof ElggUser))) // If there's an email address, use it - but only if its not from a user.
			$from = $from->email;
		else if (($site) && (isset($site->email))) // Has the current site got a from email address?
			$from = $site->email;
		else if (isset($from->url)) // If we have a url then try and use that.
		{
			$breakdown = parse_url($from->url);
			$from = 'noreply@' . $breakdown['host']; // Handle anything with a url
		}
		else // If all else fails, use the domain of the site.
			$from = 'noreply@' . get_site_domain($CONFIG->site_guid); 
	
		if (is_callable('mb_internal_encoding')) {
			mb_internal_encoding('UTF-8');
		}
		$site = get_entity($CONFIG->site_guid);
		$sitename = $site->name;
		if (is_callable('mb_encode_mimeheader')) {
			$sitename = mb_encode_mimeheader($site->name,"UTF-8", "B");
		}
		$headers = "From: \"$sitename\" <$from>\r\n"
			. "Content-Type: text/plain; charset=UTF-8; format=flowed\r\n"
    		. "MIME-Version: 1.0\r\n"
    		. "Content-Transfer-Encoding: 8bit\r\n";

    	if (is_callable('mb_encode_mimeheader')) {
			$subject = mb_encode_mimeheader($subject,"UTF-8", "B");
    	}	
    	
		// Format message
    	$message = strip_tags($message); // Strip tags from message
    	$message = preg_replace("/(\r\n|\r)/", "\n", $message); // Convert to unix line endings in body
    	$message = preg_replace("/^From/", ">From", $message); // Change lines starting with From to >From  	
    		
		return mail($to, $subject, wordwrap($message), $headers);
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
		
		register_plugin_hook('usersettings:save','user','notification_user_settings_save');
		
		//register_action("notifications/settings/usersettings/save");
		
		
		// Register some APIs
		expose_function('user.notification.get', 'get_user_notification_settings', array(
			'user_guid' => array ('type' => 'int')
		), elgg_echo('user.notification.get'));
		
		expose_function('user.notification.set', 'set_user_notification_settings', array(
			'user_guid' => array ('type' => 'int'),
			'method' => array ('type' => 'string'),
			'value' => array ('type' => 'bool')
		), elgg_echo('user.notification.set'));
		
	}
	
	function notification_user_settings_save() {
		
		global $CONFIG;
		@include($CONFIG->path . "actions/notifications/settings/usersettings/save.php");
		
	}

	// Register a startup event
	register_elgg_event_handler('init','system','notification_init',0);	
?>