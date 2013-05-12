<?php
/**
* Elgg internal messages plugin
* This plugin lets users send messages to each other.
*
* @package ElggMessages
*/


elgg_register_event_handler('init', 'system', 'messages_init');

function messages_init() {

	// register a library of helper functions
	elgg_register_library('elgg:messages', elgg_get_plugins_path() . 'messages/lib/messages.php');

	// add page menu items
	if (elgg_is_logged_in()) {
		elgg_register_menu_item('page', array(
			'name' => 'messages:inbox',
			'text' => elgg_echo('messages:inbox'),
			'href' => "messages/inbox/" . elgg_get_logged_in_user_entity()->username,
			'context' => 'messages',
		));
		
		elgg_register_menu_item('page', array(
			'name' => 'messages:sentmessages',
			'text' => elgg_echo('messages:sentmessages'),
			'href' => "messages/sent/" . elgg_get_logged_in_user_entity()->username,
			'context' => 'messages',
		));		
	}

	elgg_register_event_handler('pagesetup', 'system', 'messages_notifier');

	// Extend system CSS with our own styles, which are defined in the messages/css view
	elgg_extend_view('css/elgg', 'messages/css');
	elgg_extend_view('js/elgg', 'messages/js');
	
	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('messages', 'messages_page_handler');

	// Register a URL handler
	elgg_register_entity_url_handler('object', 'messages', 'messages_url');

	// Extend avatar hover menu
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'messages_user_hover_menu');

	// Register a notification handler for site messages
	register_notification_handler("site", "messages_site_notify_handler");
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'messages_notification_msg');
	register_notification_object('object', 'messages', elgg_echo('messages:new'));

	// delete messages sent by a user when user is deleted
	elgg_register_event_handler('delete', 'user', 'messages_purge');

	// ecml
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'messages_ecml_views_hook');

	// permission overrides
	elgg_register_plugin_hook_handler('permissions_check:metadata', 'object', 'messages_can_edit_metadata');
	elgg_register_plugin_hook_handler('permissions_check', 'object', 'messages_can_edit');
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'messages_can_edit_container');

	// Register actions
	$action_path = elgg_get_plugins_path() . 'messages/actions/messages';
	elgg_register_action("messages/send", "$action_path/send.php");
	elgg_register_action("messages/delete", "$action_path/delete.php");
	elgg_register_action("messages/process", "$action_path/process.php");
}

/**
 * Messages page handler
 *
 * @param array $page Array of URL components for routing
 * @return bool
 */
function messages_page_handler($page) {

	$current_user = elgg_get_logged_in_user_entity();
	if (!$current_user) {
		register_error(elgg_echo('noaccess'));
		$_SESSION['last_forward_from'] = current_page_url();
		forward('');
	}

	elgg_load_library('elgg:messages');

	elgg_push_breadcrumb(elgg_echo('messages'), 'messages/inbox/' . $current_user->username);

	if (!isset($page[0])) {
		$page[0] = 'inbox';
	}

	// Support the old inbox url /messages/<username>, but only if it matches the logged in user.
	// Otherwise having a username like "read" on the system could confuse this function.
	if ($current_user->username === $page[0]) {
		$page[1] = $page[0];
		$page[0] = 'inbox';
	}

	if (!isset($page[1])) {
		$page[1] = $current_user->username;
	}

	$base_dir = elgg_get_plugins_path() . 'messages/pages/messages';

	switch ($page[0]) {
		case 'inbox':
			set_input('username', $page[1]);
			include("$base_dir/inbox.php");
			break;
		case 'sent':
			set_input('username', $page[1]);
			include("$base_dir/sent.php");
			break;
		case 'read':
			set_input('guid', $page[1]);
			include("$base_dir/read.php");
			break;
		case 'compose':
		case 'add':
			include("$base_dir/send.php");
			break;
		default:
			return false;
	}
	return true;
}

/**
 * Display notification of new messages in topbar
 */
function messages_notifier() {
	if (elgg_is_logged_in()) {
		$class = "elgg-icon elgg-icon-mail";
		$text = "<span class='$class'></span>";
		$tooltip = elgg_echo("messages");
		
		// get unread messages
		$num_messages = (int)messages_count_unread();
		if ($num_messages != 0) {
			$text .= "<span class=\"messages-new\">$num_messages</span>";
			$tooltip .= " (" . elgg_echo("messages:unreadcount", array($num_messages)) . ")";
		}

		elgg_register_menu_item('topbar', array(
			'name' => 'messages',
			'href' => 'messages/inbox/' . elgg_get_logged_in_user_entity()->username,
			'text' => $text,
			'priority' => 600,
			'title' => $tooltip,
		));
	}
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
 * @param int $recipient_guid The GUID of the user to send to
 * @param int $sender_guid Optionally, the GUID of the user to send from
 * @param int $original_msg_guid The GUID of the message to reply from (default: none)
 * @param bool $notify Send a notification (default: true)
 * @param bool $add_to_sent If true (default), will add a message to the sender's 'sent' tray
 * @return bool
 */
function messages_send($subject, $body, $recipient_guid, $sender_guid = 0, $original_msg_guid = 0, $notify = true, $add_to_sent = true) {

	// @todo remove globals
	global $messagesendflag;
	$messagesendflag = 1;

	// @todo remove globals
	global $messages_pm;
	if ($notify) {
		$messages_pm = 1;
	} else {
		$messages_pm = 0;
	}

	// If $sender_guid == 0, set to current user
	if ($sender_guid == 0) {
		$sender_guid = (int) elgg_get_logged_in_user_guid();
	}

	// Initialise 2 new ElggObject
	$message_to = new ElggObject();
	$message_sent = new ElggObject();

	$message_to->subtype = "messages";
	$message_sent->subtype = "messages";

	$message_to->owner_guid = $recipient_guid;
	$message_to->container_guid = $recipient_guid;
	$message_sent->owner_guid = $sender_guid;
	$message_sent->container_guid = $sender_guid;

	$message_to->access_id = ACCESS_PUBLIC;
	$message_sent->access_id = ACCESS_PUBLIC;

	$message_to->title = $subject;
	$message_to->description = $body;

	$message_sent->title = $subject;
	$message_sent->description = $body;

	$message_to->toId = $recipient_guid; // the user receiving the message
	$message_to->fromId = $sender_guid; // the user receiving the message
	$message_to->readYet = 0; // this is a toggle between 0 / 1 (1 = read)
	$message_to->hiddenFrom = 0; // this is used when a user deletes a message in their sentbox, it is a flag
	$message_to->hiddenTo = 0; // this is used when a user deletes a message in their inbox

	$message_sent->toId = $recipient_guid; // the user receiving the message
	$message_sent->fromId = $sender_guid; // the user receiving the message
	$message_sent->readYet = 0; // this is a toggle between 0 / 1 (1 = read)
	$message_sent->hiddenFrom = 0; // this is used when a user deletes a message in their sentbox, it is a flag
	$message_sent->hiddenTo = 0; // this is used when a user deletes a message in their inbox

	$message_to->msg = 1;
	$message_sent->msg = 1;

	// Save the copy of the message that goes to the recipient
	$success = $message_to->save();

	// Save the copy of the message that goes to the sender
	if ($add_to_sent) {
		$message_sent->save();
	}

	$message_to->access_id = ACCESS_PRIVATE;
	$message_to->save();

	if ($add_to_sent) {
		$message_sent->access_id = ACCESS_PRIVATE;
		$message_sent->save();
	}

	// if the new message is a reply then create a relationship link between the new message
	// and the message it is in reply to
	if ($original_msg_guid && $success) {
		add_entity_relationship($message_sent->guid, "reply", $original_msg_guid);
	}

	$message_contents = strip_tags($body);
	if (($recipient_guid != elgg_get_logged_in_user_guid()) && $notify) {
		$recipient = get_user($recipient_guid);
		$sender = get_user($sender_guid);
		
		$subject = elgg_echo('messages:email:subject');
		$body = elgg_echo('messages:email:body', array(
			$sender->name,
			$message_contents,
			elgg_get_site_url() . "messages/inbox/" . $recipient->username,
			$sender->name,
			elgg_get_site_url() . "messages/compose?send_to=" . $sender_guid
		));

		notify_user($recipient_guid, $sender_guid, $subject, $body);
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
	$url = elgg_get_site_url() . 'messages/read/' . $message->getGUID();
	return $url;
}

function count_unread_messages() {
	elgg_deprecated_notice('Your theme is using count_unread_messages which has been deprecated for messages_count_unread()', 1.8);
	return messages_count_unread();
}

/**
 * Count the unread messages in a user's inbox
 *
 * @return int
 */
function messages_count_unread() {
	$user_guid = elgg_get_logged_in_user_guid();
	$db_prefix = elgg_get_config('dbprefix');

	// denormalize the md to speed things up.
	// seriously, 10 joins if you don't.
	$strings = array('toId', $user_guid, 'readYet', 0, 'msg', 1);
	$map = array();
	foreach ($strings as $string) {
		$id = get_metastring_id($string);
		$map[$string] = $id;
	}

	$options = array(
//		'metadata_name_value_pairs' => array(
//			'toId' => elgg_get_logged_in_user_guid(),
//			'readYet' => 0,
//			'msg' => 1
//		),
		'joins' => array(
			"JOIN {$db_prefix}metadata msg_toId on e.guid = msg_toId.entity_guid",
			"JOIN {$db_prefix}metadata msg_readYet on e.guid = msg_readYet.entity_guid",
			"JOIN {$db_prefix}metadata msg_msg on e.guid = msg_msg.entity_guid",
		),
		'wheres' => array(
			"msg_toId.name_id='{$map['toId']}' AND msg_toId.value_id='{$map[$user_guid]}'",
			"msg_readYet.name_id='{$map['readYet']}' AND msg_readYet.value_id='{$map[0]}'",
			"msg_msg.name_id='{$map['msg']}' AND msg_msg.value_id='{$map[1]}'",
		),
		'owner_guid' => $user_guid,
		'count' => true,
	);

	return elgg_get_entities_from_metadata($options);
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
	$user = $params['entity'];

	if (elgg_is_logged_in() && elgg_get_logged_in_user_guid() != $user->guid) {
		$url = "messages/compose?send_to={$user->guid}";
		$item = new ElggMenuItem('send', elgg_echo('messages:sendmessage'), $url);
		$item->setSection('action');
		$return[] = $item;
	}

	return $return;
}

/**
 * Delete messages from a user who is being deleted
 *
 * @param string   $event Event name
 * @param string   $type  Event type
 * @param ElggUser $user  User being deleted
 */
function messages_purge($event, $type, $user) {

	if (!$user->getGUID()) {
		return;
	}

	// make sure we delete them all
	$entity_disable_override = access_get_show_hidden_status();
	access_show_hidden_entities(true);
	$ia = elgg_set_ignore_access(true);

	$options = array(
		'type' => 'object',
		'subtype' => 'messages',
		'metadata_name' => 'fromId',
		'metadata_value' => $user->getGUID(),
		'limit' => 0,
	);
	$batch = new ElggBatch('elgg_get_entities_from_metadata', $options);
	foreach ($batch as $e) {
		$e->delete();
	}

	elgg_set_ignore_access($ia);
	access_show_hidden_entities($entity_disable_override);
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
