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

	 * @author Curverider Ltd

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
							error_log(sprintf(elgg_echo('NotificationException:NoHandlerFound'), $method));
		
						if ($CONFIG->debug)
							error_log("Sending message to $guid using $method");					
							
						// Trigger handler and retrieve result.
						try {
							$result[$guid][$method] = $handler(
								$from ? get_entity($from) : NULL, 	// From entity
								get_entity($guid), 					// To entity
								$subject,							// The subject
								$message, 			// Message
								$params								// Params
							);
						} catch (Exception $e) {
							error_log($e->getMessage());
						}
						
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
		
		$header_eol = "\r\n";
		if ( 
			(isset($CONFIG->broken_mta)) &&
			($CONFIG->broken_mta)
		)
			$header_eol = "\n"; // Allow non-RFC 2822 mail headers to support some broken MTAs
		
		$from_email = "\"$sitename\" <$from>";
		if (strtolower(substr(PHP_OS, 0 , 3)) == 'win')
			$from_email = "$from"; // Windows is somewhat broken, so we use a different format from header
			
		$headers = "From: $from_email{$header_eol}"
			. "Content-Type: text/plain; charset=UTF-8; format=flowed{$header_eol}"
    		. "MIME-Version: 1.0{$header_eol}"
    		. "Content-Transfer-Encoding: 8bit{$header_eol}";

    	if (is_callable('mb_encode_mimeheader')) {
			$subject = mb_encode_mimeheader($subject,"UTF-8", "B");
    	}	
    	
		// Format message
		$message = html_entity_decode($message, ENT_COMPAT, 'UTF-8'); // Decode any html entities
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
	
	/**
	 * Register an entity type and subtype to be eligible for notifications
	 *
	 * @param string $entity_type The type of entity
	 * @param string $object_subtype Its subtype
	 * @param string $english_name It's English notification string (eg "New blog post")
	 */
	function register_notification_object($entity_type, $object_subtype, $english_name) {
		global $CONFIG;
		
		if ($entity_type == '') $entity_type = '__BLANK__';
		if ($object_subtype == '') $object_subtype = '__BLANK__';
		
		if (!isset($CONFIG->register_objects)) {
			$CONFIG->register_objects = array();
		}
		if (!isset($CONFIG->register_objects[$entity_type])) {
			$CONFIG->register_objects[$entity_type] = array();
		}
		$CONFIG->register_objects[$entity_type][$object_subtype] = $english_name;
	}
	
	/**
	 * Establish a 'notify' relationship between the user and a content author
	 *
	 * @param int $user_guid The GUID of the user who wants to follow a user's content
	 * @param int $author_guid The GUID of the user whose content the user wants to follow
	 * @return true|false Depending on success
	 */
	function register_notification_interest($user_guid, $author_guid) {
		return add_entity_relationship($user_guid, 'notify', $author_guid);
	}
	
	/**
	 * Remove a 'notify' relationship between the user and a content author
	 *
	 * @param int $user_guid The GUID of the user who is following a user's content
	 * @param int $author_guid The GUID of the user whose content the user wants to unfollow
	 * @return true|false Depending on success
	 */
	function remove_notification_interest($user_guid, $author_guid) {
		return remove_entity_relationship($user_guid, 'notify', $author_guid);
	}
	
	/**
	 * Automatically triggered notification on 'create' events that looks at registered
	 * objects and attempts to send notifications to anybody who's interested
	 *
	 * @see register_notification_object
	 */
	function object_notifications($event, $object_type, $object) {
		
		// We only want to trigger notification events for ElggEntities
		if ($object instanceof ElggEntity) {
			
			// Get config data
			global $CONFIG, $SESSION, $NOTIFICATION_HANDLERS;
			
			$hookresult = trigger_plugin_hook('object:notifications',$object_type,array(
										'event' => $event,
										'object_type' => $object_type,
										'object' => $object,
									),false);
			if ($hookresult === true) return true;
			
			// Have we registered notifications for this type of entity?
			$object_type = $object->getType(); if (empty($object_type)) $object_type = '__BLANK__';
			$object_subtype = $object->getSubtype(); if (empty($object_subtype)) $object_subtype = '__BLANK__';
			if (isset($CONFIG->register_objects[$object_type][$object_subtype])) {

				$descr = $CONFIG->register_objects[$object_type][$object_subtype];
				$string = $descr . ": " . $object->getURL();
				
				// Get users interested in content from this person and notify them
				// (Person defined by container_guid so we can also subscribe to groups if we want)
				foreach($NOTIFICATION_HANDLERS as $method => $foo)
				if ($interested_users = get_entities_from_relationship('notify' . $method,$object->container_guid,true,'user','',0,'',99999)) {
					
					if (is_array($interested_users))
						foreach($interested_users as $user) {
							if ($user instanceof ElggUser) {
								
								if (!$user->isBanned())
								if (($user->guid != $SESSION['user']->guid) && has_access_to_entity($object,$user)
									&& $object->access_id != ACCESS_PRIVATE) { 
		
												$methodstring = trigger_plugin_hook('notify:entity:message',$object->getType(),array(
													'entity' => $object,
													'to_entity' => $user,
													'method' => $method),$string);
												if (empty($methodstring) && $methodstring !== false) $methodstring = $string;
												if ($methodstring !== false)
													notify_user($user->guid,$object->container_guid,$descr,$methodstring,NULL,array($method));
								}
							}						
						}
				}
				
			}
			
		}
		
	}

	// Register a startup event
	register_elgg_event_handler('init','system','notification_init',0);
	register_elgg_event_handler('create','object','object_notifications');

?>
