<?php
/**
* Elgg internal messages plugin
* This plugin lets users send messages to each other.
*
* @package ElggMessages
*/


elgg_register_event_handler('init', 'system', 'messages_init');

function messages_init() {

	// add submenu options
	if (elgg_get_context() == "messages") {
		add_submenu_item(elgg_echo('messages:inbox'), "pg/messages/" . get_loggedin_user()->username);
		add_submenu_item(elgg_echo('messages:sentmessages'), "mod/messages/sent.php");
	}

	// Extend system CSS with our own styles, which are defined in the shouts/css view
	elgg_extend_view('css/screen', 'messages/css');

	// Add icon to the topbar
	elgg_extend_view('elgg_topbar/extend', 'messages/topbar');

	// Register a page handler, so we can have nice URLs
	register_page_handler('messages', 'messages_page_handler');

	// Register a URL handler
	register_entity_url_handler('messages_url', 'object', 'messages');

	// Extend avatar hover menu
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'messages_user_hover_menu');

	// Register a notification handler for site messages
	register_notification_handler("site", "messages_site_notify_handler");
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'messages_notification_msg');
	register_notification_object('object', 'messages', elgg_echo('messages:new'));

	// ecml
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'messages_ecml_views_hook');

	// permission overrides
	elgg_register_plugin_hook_handler('permissions_check:metadata', 'object', 'messages_can_edit_metadata');
	elgg_register_plugin_hook_handler('permissions_check', 'object', 'messages_can_edit');
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'messages_can_edit_container');

	// Register actions
	$action_path = elgg_get_plugin_path() . 'messages/actions';
	elgg_register_action("messages/send", "$action_path/send.php");
	elgg_register_action("messages/delete", "$action_path/delete.php");
}

/**
 * Messages page handler
 *
 * @param array $page Array of URL components for routing
 * @return bool
 */
function messages_page_handler($page) {

	// The first component of a messages URL is the username
	if (isset($page[0])) {
		set_input('username', $page[0]);
	}

	// The second part dictates what we're doing
	if (isset($page[1])) {
		switch($page[1]) {
			case "read":
				set_input('message',$page[2]);
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
 * Prevent messages from generating a notification
 */
function messages_notification_msg($hook_name, $entity_type, $return_value, $params) {

	if ($params['entity'] instanceof ElggEntity) {
		if ($params['entity']->getSubtype() == 'messages') {
			return false;
		}
	}
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
 * @return bool
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
	if ($from == 0) {
		$from = (int) get_loggedin_userid();
	}

	// Initialise 2 new ElggObject
	$message_to = new ElggObject();
	$message_sent = new ElggObject();
	$message_to->subtype = "messages";
	$message_sent->subtype = "messages";
	$message_to->owner_guid = $send_to;
	$message_to->container_guid = $send_to;
	$message_sent->owner_guid = $from;
	$message_sent->container_guid = $from;
	$message_to->access_id = ACCESS_PUBLIC;
	$message_sent->access_id = ACCESS_PUBLIC;
	$message_to->title = $subject;
	$message_to->description = $body;
	$message_sent->title = $subject;
	$message_sent->description = $body;
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
	if ($add_to_sent) {
		$success2 = $message_sent->save();
	}

	$message_to->access_id = ACCESS_PRIVATE;
	$message_to->save();

	if ($add_to_sent) {
		$message_sent->access_id = ACCESS_PRIVATE;
		$message_sent->save();
	}

	// if the new message is a reply then create a relationship link between the new message
	// and the message it is in reply to
	if ($reply && $success){
		$create_relationship = add_entity_relationship($message_sent->guid, "reply", $reply);
	}

	$message_contents = strip_tags($body);
	if ($send_to != get_loggedin_user() && $notify) {
		$subject = elgg_echo('messages:email:subject');
		$body = elgg_echo('messages:email:body', array(
			get_loggedin_user()->name,
			$message_contents,
			elgg_get_site_url() . "pg/messages/" . $user->username,
			get_loggedin_user()->name,
			elgg_get_site_url() . "mod/messages/send.php?send_to=" . get_loggedin_userid()
		));

		notify_user($send_to, get_loggedin_userid(), $subject, $body);
	}

	$messagesendflag = 0;
	return $success;
}

/**
 * Message URL override
 *
 * @param ElggObject $message
 * @return string
 */
function messages_url($message) {
	$url = elgg_get_site_url() . 'pg/messages/';
	$url .= $message->getOwnerEntity()->username . '/read/' . $message->getGUID();
	return $url;
}

/**
 * Count the unread messages in a user's inbox
 *
 * @return int
 */
function messages_count_unread() {

	$options = array(
		'metadata_name_value_pairs' => array(
			'toId' => get_loggedin_userid(),
			'readYet' => 0,
			'msg' => 1
		),
		'owner_guid' => get_loggedin_userid()
	);
	$num_messages = elgg_get_entities_from_metadata($options);

	if (is_array($num_messages)) {
		return sizeof($num_messages);
	}

	return 0;
}

/**
 * Notification handler
 *
 * @param ElggEntity $from
 * @param ElggUser   $to
 * @param string     $subject
 * @param string     $message
 * @param array      $params
 * @return bool
 */
function messages_site_notify_handler(ElggEntity $from, ElggUser $to, $subject, $message, array $params = NULL) {

	if (!$from) {
		throw new NotificationException(elgg_echo('NotificationException:MissingParameter', array('from')));
	}

	if (!$to) {
		throw new NotificationException(elgg_echo('NotificationException:MissingParameter', array('to')));
	}

	global $messages_pm;
	if (!$messages_pm) {
		return messages_send($subject, $message, $to->guid, $from->guid, 0, false, false);
	}

	return true;
}

/**
 * Add to the user hover menu
 */
function messages_user_hover_menu($hook, $type, $return, $params) {
	$user = $params['user'];

	$url = "mod/messages/send.php?send_to={$user->guid}";
	$item = new ElggMenuItem('logbrowser', elgg_echo('messages:sendmessage'), $url);
	$item->setSection('action');
	elgg_register_menu_item('user_hover', $item);
}


/**
 * Register messages with ECML.
 *
 * @param string $hook
 * @param string $entity_type
 * @param array $return_value
 * @param unknown_type $params
 */
function messages_ecml_views_hook($hook, $entity_type, $return_value, $params) {
	$return_value['messages/messages'] = elgg_echo('messages');

	return $return_value;
}
