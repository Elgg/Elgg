<?php

/**
* Elgg internal messages plugin
* This plugin lets user send each other messages.
* 
* @package ElggMessages
*/

/**
* Messages initialisation
*
* These parameters are required for the event API, but we won't use them:
* 
* @param unknown_type $event
* @param unknown_type $object_type
* @param unknown_type $object
*/
function messages_init() {
    
    // Load system configuration
		global $CONFIG;
		
	//add submenu options
		if (get_context() == "messages") {
			add_submenu_item(elgg_echo('messages:inbox'), elgg_get_site_url() . "pg/messages/" . get_loggedin_user()->username);
			add_submenu_item(elgg_echo('messages:sentmessages'), elgg_get_site_url() . "mod/messages/sent.php");
		}
		
	// Extend system CSS with our own styles, which are defined in the shouts/css view
		elgg_extend_view('css','messages/css');
		
	// Extend the elgg topbar
		elgg_extend_view('elgg_topbar/extend','messages/topbar');
	
	// Register a page handler, so we can have nice URLs
		register_page_handler('messages','messages_page_handler');
		
	// Register a URL handler for shouts posts
		register_entity_url_handler('messages_url','object','messages');
		
    // Extend avatar user-menu	
		elgg_extend_view('profile/menu/links','messages/menu');
		
	// Register a notification handler for site messages
		register_notification_handler("site", "messages_site_notify_handler");
		register_plugin_hook('notify:entity:message','object','messages_notification_msg');
		register_notification_object('object','messages',elgg_echo('messages:new'));
			    
	// Override metadata permissions
	    register_plugin_hook('permissions_check:metadata','object','messages_can_edit_metadata');
	    
	// ecml
	register_plugin_hook('get_views', 'ecml', 'messages_ecml_views_hook');

}

/**
 * Override the canEditMetadata function to return true for messages
 *
 */
function messages_can_edit_metadata($hook_name, $entity_type, $return_value, $parameters) {

	global $messagesendflag;
	
	if ($messagesendflag == 1) {
		$entity = $parameters['entity'];
		if ($entity->getSubtype() == "messages") {
			return true;
		}
	}
	
	return $return_value;
	
}

/**
 * Override the canEdit function to return true for messages within a particular context.
 *
 */
function messages_can_edit($hook_name, $entity_type, $return_value, $parameters) {
	
	global $messagesendflag;
	
	if ($messagesendflag == 1) {
		$entity = $parameters['entity'];
		if ($entity->getSubtype() == "messages") {
			return true;
		}
	}
	
	return $return_value;
	
}

/**
 * We really don't want to send a notification message when a message is sent, if the method is messages ...
 *
 */
function messages_notification_msg($hook_name, $entity_type, $return_value, $parameters) {

	global $CONFIG, $messages_pm;
	
	if ($parameters['entity'] instanceof ElggEntity) {
		
		if ($parameters['entity']->getSubtype() == 'messages') {
			
			return false;
			/*if (!$messages_pm) return false;
			if ($parameters['method'] == 'email') {
				return sprintf(
							elgg_echo('messages:email:body'),
							get_loggedin_user()->name,
							strip_tags($parameters['entity']->description),
							elgg_get_site_url() . "pg/messages/" . $user->username,
							get_loggedin_user()->name,
							elgg_get_site_url() . "mod/messages/send.php?send_to=" . get_loggedin_userid()
						);
			} else if ($parameters['method'] == 'site') return false;*/
		}
	}
	return null;
	
}

/**
 * Override the canEdit function to return true for messages within a particular context.
 *
 */
function messages_can_edit_container($hook_name, $entity_type, $return_value, $parameters) {
	
	global $messagesendflag;
	
	if ($messagesendflag == 1) {
		return true;
	}
	
	return $return_value;
	
}

/**
 * Send an internal message
 *
 * @param string $subject The subject line of the message
 * @param string $body The body of the mesage
 * @param int $send_to The GUID of the user to send to
 * @param int $from Optionally, the GUID of the user to send from
 * @param int $reply The GUID of the message to reply from (default: none)
 * @param true|false $notify Send a notification (default: true)
 * @param true|false $add_to_sent If true (default), will add a message to the sender's 'sent' tray
 * @return true|false Depending on success
 */
function messages_send($subject, $body, $send_to, $from = 0, $reply = 0, $notify = true, $add_to_sent = true) {
	
		global $messagesendflag;
		$messagesendflag = 1;
		
		global $messages_pm;
		if ($notify) {
			$messages_pm = 1;
		} else {
			$messages_pm = 0;
		}
		
	// If $from == 0, set to current user
			if ($from == 0)
				$from = (int) get_loggedin_userid();
				
    // Initialise a new ElggObject
			$message_to = new ElggObject();
			$message_sent = new ElggObject();
	// Tell the system it's a message
			$message_to->subtype = "messages";
			$message_sent->subtype = "messages";
	// Set its owner to the current user
			// $message_to->owner_guid = get_loggedin_userid();
			$message_to->owner_guid = $send_to;
			$message_to->container_guid = $send_to;
			$message_sent->owner_guid = $from;
			$message_sent->container_guid = $from;
	// For now, set its access to public (we'll add an access dropdown shortly)
			$message_to->access_id = ACCESS_PUBLIC;
			$message_sent->access_id = ACCESS_PUBLIC;
	// Set its description appropriately
			$message_to->title = $subject;
			$message_to->description = $body;
			$message_sent->title = $subject;
			$message_sent->description = $body;
    // set the metadata
            $message_to->toId = $send_to; // the user receiving the message
            $message_to->fromId = $from; // the user receiving the message
            $message_to->readYet = 0; // this is a toggle between 0 / 1 (1 = read)
            $message_to->hiddenFrom = 0; // this is used when a user deletes a message in their sentbox, it is a flag
            $message_to->hiddenTo = 0; // this is used when a user deletes a message in their inbox
            $message_sent->toId = $send_to; // the user receiving the message
            $message_sent->fromId = $from; // the user receiving the message
            $message_sent->readYet = 0; // this is a toggle between 0 / 1 (1 = read)
            $message_sent->hiddenFrom = 0; // this is used when a user deletes a message in their sentbox, it is a flag
            $message_sent->hiddenTo = 0; // this is used when a user deletes a message in their inbox
            
            $message_to->msg = 1;
            $message_sent->msg = 1;
            
	    // Save the copy of the message that goes to the recipient
			$success = $message_to->save();
			
		// Save the copy of the message that goes to the sender
			if ($add_to_sent) $success2 = $message_sent->save();
			
			$message_to->access_id = ACCESS_PRIVATE;
			$message_to->save();
			
			if ($add_to_sent) {
				$message_sent->access_id = ACCESS_PRIVATE;
				$message_sent->save();
			}
			
	    // if the new message is a reply then create a relationship link between the new message
	    // and the message it is in reply to
	        if($reply && $success){
    	        $create_relationship = add_entity_relationship($message_sent->guid, "reply", $reply);		    	        
	        }
	        
	        
	        global $CONFIG;
			$message_contents = strip_tags($body);
			if ($send_to != get_loggedin_user() && $notify)
			notify_user($send_to, get_loggedin_userid(), elgg_echo('messages:email:subject'), 
				sprintf(
							elgg_echo('messages:email:body'),
							get_loggedin_user()->name,
							$message_contents,
							elgg_get_site_url() . "pg/messages/" . $user->username,
							get_loggedin_user()->name,
							elgg_get_site_url() . "mod/messages/send.php?send_to=" . get_loggedin_userid()
						)
			);
			
	    	$messagesendflag = 0;    
	        return $success;
	
}

/**
 * messages page handler; allows the use of fancy URLs
 *
 * @param array $page From the page_handler function
 * @return true|false Depending on success
 */
function messages_page_handler($page) {
	
	// The first component of a messages URL is the username
	if (isset($page[0])) {
		set_input('username',$page[0]);
	}
	
	// The second part dictates what we're doing
	if (isset($page[1])) {
		switch($page[1]) {
			case "read":		set_input('message',$page[2]);
								include(dirname(__FILE__) . "/read.php");
								return true;
								break;
		}
	// If the URL is just 'messages/username', or just 'messages/', load the standard messages index
	} else {
		include(dirname(__FILE__) . "/index.php");
		return true;
	}
	
	return false;
	
}

function messages_url($message) {
	
	global $CONFIG;
	return $CONFIG->url . "pg/messages/" . $message->getOwnerEntity()->username . "/read/" . $message->getGUID();
	
}

// A simple function to count the number of messages that are unread in a user's inbox
function count_unread_messages() {
    
    //get the users inbox messages
    //$num_messages = get_entities_from_metadata("toId", get_loggedin_userid(), "object", "messages", 0, 10, 0, "", 0, false);
   $num_messages = elgg_get_entities_from_metadata(array('metadata_name_value_pairs' => array(
    							'toId' => get_loggedin_userid(),
    							'readYet' => 0,
    							'msg' => 1
    						), 'owner_guid' => get_loggedin_userid()));

	if (is_array($num_messages))
		$counter = sizeof($num_messages);
	else
		$counter = 0;
		
    return $counter;
    
}

function messages_site_notify_handler(ElggEntity $from, ElggUser $to, $subject, $message, array $params = NULL)
{
	global $CONFIG;
	
	if (!$from)
		throw new NotificationException(sprintf(elgg_echo('NotificationException:MissingParameter'), 'from'));
		 
	if (!$to)
		throw new NotificationException(sprintf(elgg_echo('NotificationException:MissingParameter'), 'to'));
		
	global $messages_pm;
	if (!$messages_pm)
		return messages_send($subject,$message,$to->guid,$from->guid,0,false,false);
	else return true;
	
}
/**
 * Register messages with ECML.
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $return_value
 * @param unknown_type $params
 */
function messages_ecml_views_hook($hook, $entity_type, $return_value, $params) {
	$return_value['messages/messages'] = elgg_echo('messages');

	return $return_value;
}


// Make sure the messages initialisation function is called on initialisation
register_elgg_event_handler('init','system','messages_init');

register_plugin_hook('permissions_check','object','messages_can_edit');
register_plugin_hook('container_permissions_check','object','messages_can_edit_container');

// Register actions
global $CONFIG;
register_action("messages/send",false,$CONFIG->pluginspath . "messages/actions/send.php");
register_action("messages/delete",false,$CONFIG->pluginspath . "messages/actions/delete.php");