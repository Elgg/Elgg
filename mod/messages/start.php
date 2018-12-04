<?php
/**
* Elgg internal messages plugin
* This plugin lets users send messages to each other.
*/

/**
 * Messages init
 *
 * @return void
 */
function messages_init() {

	// add page menu items
	$user = elgg_get_logged_in_user_entity();
	if (!empty($user)) {
		elgg_register_menu_item('page', [
			'name' => 'messages:inbox',
			'text' => elgg_echo('messages:inbox'),
			'href' => elgg_generate_url('collection:object:messages:owner', [
				'username' => $user->username,
			]),
			'context' => 'messages',
		]);
		
		elgg_register_menu_item('page', [
			'name' => 'messages:sentmessages',
			'text' => elgg_echo('messages:sentmessages'),
			'href' => elgg_generate_url('collection:object:messages:sent', [
				'username' => $user->username,
			]),
			'context' => 'messages',
		]);
	}

	// Extend system CSS with our own styles, which are defined in the messages/css view
	elgg_extend_view('elgg.css', 'messages/css');
	elgg_extend_view('elgg.js', 'messages/js');

	// Extend avatar hover menu
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'messages_user_hover_menu');
	elgg_register_plugin_hook_handler('register', 'menu:title', 'messages_user_hover_menu');

	// delete messages sent by a user when user is deleted
	elgg_register_event_handler('delete', 'user', 'messages_purge');

	// ecml
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'messages_ecml_views_hook');

	// permission overrides
	elgg_register_plugin_hook_handler('permissions_check:metadata', 'object', 'messages_can_edit_metadata');
	elgg_register_plugin_hook_handler('permissions_check', 'object', 'messages_can_edit');
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'messages_can_edit_container');

	// Topbar menu. We assume this menu will render *after* a message is rendered. If a refactor/plugin
	// causes it to render first, the unread count notification will not update until the next page.
	elgg_register_plugin_hook_handler('register', 'menu:topbar', 'messages_register_topbar');
}

/**
 * Add inbox link to topbar
 *
 * @param string         $hook   "register"
 * @param string         $type   "menu:topbar"
 * @param ElggMenuItem[] $items  Menu items
 * @param array          $params Hook params
 *
 * @return void|ElggMenuItem[]
 */
function messages_register_topbar($hook, $type, $items, $params) {
	if (!elgg_is_logged_in()) {
		return;
	}

	$user = elgg_get_logged_in_user_entity();

	$text = elgg_echo('messages');
	$title = $text;

	$num_messages = (int) messages_count_unread();
	if ($num_messages) {
		$title .= " (" . elgg_echo("messages:unreadcount", [$num_messages]) . ")";
	}

	$items[] = ElggMenuItem::factory([
		'name' => 'messages',
		'href' => elgg_generate_url('collection:object:messages:owner', [
			'username' => $user->username,
		]),
		'text' => $text,
		'priority' => 600,
		'title' => $title,
		'icon' => 'mail',
		'badge' => $num_messages ? $num_messages : null,
	]);

	return $items;
}

/**
 * Override the canEditMetadata function to return true for messages
 *
 * @param string $hook         'permissions_check:metadata'
 * @param string $type         'object'
 * @param bool   $return_value current return value
 * @param array  $parameters   supplied params
 *
 * @return void|true
 */
function messages_can_edit_metadata($hook, $type, $return_value, $parameters) {

	global $messagesendflag;

	if ($messagesendflag !== 1) {
		return;
	}
	
	$entity = elgg_extract('entity', $parameters);
	if ($entity instanceof ElggObject && $entity->getSubtype() == 'messages') {
		return true;
	}
}

/**
 * Override the canEdit function to return true for messages within a particular context
 *
 * @param string $hook         'permissions_check'
 * @param string $type         'object'
 * @param bool   $return_value current return value
 * @param array  $parameters   supplied params
 *
 * @return void|true
 */
function messages_can_edit($hook, $type, $return_value, $parameters) {

	global $messagesendflag;
	
	if ($messagesendflag !== 1) {
		return;
	}
	
	$entity = elgg_extract('entity', $parameters);
	if ($entity instanceof ElggObject && $entity->getSubtype() == 'messages') {
		return true;
	}
}

/**
 * Override the canEdit function to return true for messages within a particular context
 *
 * @param string $hook         'container_permissions_check'
 * @param string $type         'object'
 * @param bool   $return_value current return value
 * @param array  $parameters   supplied params
 *
 * @return void|true
 */
function messages_can_edit_container($hook, $type, $return_value, $parameters) {

	global $messagesendflag;

	if ($messagesendflag == 1) {
		return true;
	}
}

/**
 * Send an internal message
 *
 * @param string $subject           The subject line of the message
 * @param string $body              The body of the mesage
 * @param int    $recipient_guid    The GUID of the user to send to
 * @param int    $sender_guid       Optionally, the GUID of the user to send from
 * @param int    $original_msg_guid The GUID of the message to reply from (default: none)
 * @param bool   $notify            Send a notification (default: true)
 * @param bool   $add_to_sent       If true (default), will add a message to the sender's 'sent' tray
 *
 * @return false|int
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

	$message_to = new ElggMessage();
	$message_sent = new ElggMessage();

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

	if (($recipient_guid != elgg_get_logged_in_user_guid()) && $notify) {
		$message_contents = $body;
		$recipient = get_user($recipient_guid);
		$sender = get_user($sender_guid);
		
		$subject = elgg_echo('messages:email:subject', [], $recipient->language);
		$body = elgg_echo('messages:email:body', [
				$sender->getDisplayName(),
				$message_contents,
				elgg_generate_url('collection:object:messages:owner', [
					'username' => $recipient->username,
				]),
				$sender->getDisplayName(),
				elgg_generate_url('add:object:messages', [
					'send_to' => $sender_guid,
				]),
			],
			$recipient->language
		);

		$params = [
			'object' => $message_to,
			'action' => 'send',
			'url' => $message_to->getURL(),
		];
		notify_user($recipient_guid, $sender_guid, $subject, $body, $params);
	}

	$messagesendflag = 0;
	return $success;
}

/**
 * Message URL override
 *
 * @param string $hook   'entity:url'
 * @param string $type   'object'
 * @param string $url    current return value
 * @param array  $params supplied params
 *
 * @return void|string
 * @deprecated 3.0 use ElggEntity::getURL()
 */
function messages_set_url($hook, $type, $url, $params) {
	
	$entity = elgg_extract('entity', $params);
	if (!$entity instanceof ElggObject || $entity->getSubtype() !== 'messages') {
		return;
	}
	
	elgg_deprecated_notice(__METHOD__ . ' is deprecated please use ElggEntity::getURL()', '3.0');
	
	return elgg_generate_entity_url($entity);
}

/**
 * Returns the unread messages in a user's inbox
 *
 * @param int  $user_guid GUID of user whose inbox we're counting (0 for logged in user)
 * @param int  $limit     Number of unread messages to return (default from settings)
 * @param int  $offset    Start at a defined offset (for listings)
 * @param bool $count     Switch between entities array or count mode
 *
 * @return ElggObject[]|int
 * @since 1.9
 */
function messages_get_unread($user_guid = 0, $limit = null, $offset = 0, $count = false) {
	if (!$user_guid) {
		$user_guid = elgg_get_logged_in_user_guid();
	}

	return elgg_get_entities([
		'type' => 'object',
		'subtype' => 'messages',
		'metadata_name_value_pairs' => [
			'toId' => (int) $user_guid,
			'readYet' => 0,
		],
		'owner_guid' => (int) $user_guid,
		'limit' => $limit ? : elgg_get_config('default_limit'),
		'offset' => $offset,
		'count' => $count,
		'distinct' => false,
	]);
}

/**
 * Count the unread messages in a user's inbox
 *
 * @param int $user_guid GUID of user whose inbox we're counting (0 for logged in user)
 *
 * @return int
 */
function messages_count_unread($user_guid = 0) {
	return messages_get_unread($user_guid, 10, 0, true);
}

/**
 * Prepare the compose form variables
 *
 * @param int $recipient_guid new message recipient
 *
 * @return array
 */
function messages_prepare_form_vars($recipient_guid = 0) {

	$recipients = [];
	$recipient = get_user($recipient_guid);
	if (!empty($recipient)) {
		$recipients[] = $recipient->getGUID();
	}

	// input names => defaults
	$values = [
		'subject' => elgg_get_sticky_value('messages', 'subject', ''),
		'body' => elgg_get_sticky_value('messages', 'body', ''),
		'recipients' => elgg_get_sticky_value('messages', 'recipients', $recipients),
	];

	elgg_clear_sticky_form('messages');

	return $values;
}

/**
 * Add to the user hover menu
 *
 * @param string         $hook   'register'
 * @param string         $type   'menu:user_hover' or 'menu:title'
 * @param ElggMenuItem[] $return current return value
 * @param array          $params supplied params
 *
 * @return void|ElggMenuItem[]
 */
function messages_user_hover_menu($hook, $type, $return, $params) {
	
	$user = elgg_extract('entity', $params);
	if (!elgg_is_logged_in() || !$user instanceof ElggUser) {
		return;
	}
	
	if (elgg_get_logged_in_user_guid() === $user->guid) {
		return;
	}
	
	$menu_options = [
		'name' => 'send',
		'text' => elgg_echo('messages:sendmessage'),
		'icon' => 'mail',
		'href' => elgg_generate_url('add:object:messages', [
			'send_to' => $user->guid,
		]),
	];
	
	if ($type == 'menu:user_hover') {
		$menu_options['section'] = 'action';
	}
	
	if ($type == 'menu:title') {
		$menu_options['class'] = ['elgg-button', 'elgg-button-action'];
	}
	
	$return[] = ElggMenuItem::factory($menu_options);

	return $return;
}

/**
 * Delete messages from a user who is being deleted
 *
 * @param string   $event Event name
 * @param string   $type  Event type
 * @param ElggUser $user  User being deleted
 *
 * @return void
 */
function messages_purge($event, $type, $user) {

	if (!$user->getGUID()) {
		return;
	}

	// make sure we delete them all
	$entity_disable_override = access_show_hidden_entities(true);
	$ia = elgg_set_ignore_access(true);

	$options = [
		'type' => 'object',
		'subtype' => 'messages',
		'metadata_name_value_pairs' => [
			'fromId' => $user->guid,
		],
		'limit' => false,
	];
	$batch = new ElggBatch('elgg_get_entities', $options);
	$batch->setIncrementOffset(false);
	foreach ($batch as $e) {
		$e->delete();
	}

	elgg_set_ignore_access($ia);
	access_show_hidden_entities($entity_disable_override);
}

/**
 * Register messages with ECML.
 *
 * @param string $hook         'get_views'
 * @param string $type         'ecml'
 * @param string $return_value current return value
 * @param array  $params       supplied params
 *
 * @return array
 */
function messages_ecml_views_hook($hook, $type, $return_value, $params) {
	$return_value['messages/messages'] = elgg_echo('messages');

	return $return_value;
}

return function() {
	elgg_register_event_handler('init', 'system', 'messages_init');
};
