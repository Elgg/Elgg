<?php
/**
 * Discussion plugin
 */

/**
 * Initialize the discussion component
 *
 * @return void
 */
function discussion_init() {

	elgg_register_page_handler('discussion', 'discussion_page_handler');

	elgg_register_plugin_hook_handler('entity:url', 'object', 'discussion_set_topic_url');

	// prevent comments on closed discussions
	elgg_register_plugin_hook_handler('permissions_check:comment', 'object', 'discussion_comment_permissions');

	// add link to owner block
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'discussion_owner_block_menu');

	// add the forum tool option
	add_group_tool_option('forum', elgg_echo('groups:enableforum'), true);
	elgg_extend_view('groups/tool_latest', 'discussion/group_module');

	// notifications
	elgg_register_plugin_hook_handler('get', 'subscriptions', 'discussion_get_subscriptions');
	elgg_register_notification_event('object', 'discussion');
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:discussion', 'discussion_prepare_notification');
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:comment', 'discussion_prepare_comment_notification');

	// allow to be liked
	elgg_register_plugin_hook_handler('likes:is_likable', 'object:discussion', 'Elgg\Values::getTrue');

	// Add latest discussions tab to /groups/all page
	elgg_register_plugin_hook_handler('register', 'menu:filter:groups/all', 'discussion_setup_groups_filter_tabs');
}

/**
 * Discussion page handler
 *
 * URLs take the form of
 *  All topics in site:    discussion/all
 *  List topics in forum:  discussion/owner/<guid>
 *  View discussion topic: discussion/view/<guid>
 *  Add discussion topic:  discussion/add/<guid>
 *  Edit discussion topic: discussion/edit/<guid>
 *
 * @param array $page Array of url segments for routing
 * @return bool
 */
function discussion_page_handler($page) {

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	elgg_push_breadcrumb(elgg_echo('discussion'), 'discussion/all');

	switch ($page[0]) {
		case 'all':
			echo elgg_view_resource('discussion/all');
			break;
		case 'owner':
			echo elgg_view_resource('discussion/owner', [
				'guid' => elgg_extract(1, $page),
			]);
			break;
		case 'group':
			echo elgg_view_resource('discussion/group', [
				'guid' => elgg_extract(1, $page),
			]);
			break;
		case 'add':
			echo elgg_view_resource('discussion/add', [
				'guid' => elgg_extract(1, $page),
			]);
			break;
		case 'edit':
			echo elgg_view_resource('discussion/edit', [
				'guid' => elgg_extract(1, $page),
			]);
			break;
		case 'view':
			echo elgg_view_resource('discussion/view', [
				'guid' => elgg_extract(1, $page),
			]);
			break;
		default:
			return false;
	}
	return true;
}

/**
 * Override the url for discussion topics
 *
 * @param string $hook   'entity:url'
 * @param string $type   'object'
 * @param string $url    current value
 * @param array  $params supplied params
 *
 * @return void|string
 */
function discussion_set_topic_url($hook, $type, $url, $params) {
	
	$entity = elgg_extract('entity', $params);
	if (!elgg_instanceof($entity, 'object', 'discussion')) {
		return;
	}

	
	$title = elgg_get_friendly_title($entity->getDisplayName());
	return "discussion/view/{$entity->guid}/{$title}";
}

/**
 * Add owner block link for groups
 *
 * @param string         $hook   'register'
 * @param string         $type   'menu:owner_block'
 * @param ElggMenuItem[] $return current return value
 * @param array          $params supplied params
 *
 * @return void|ElggMenuItem[]
 */
function discussion_owner_block_menu($hook, $type, $return, $params) {
	
	$entity = elgg_extract('entity', $params);
	if (!$entity instanceof ElggGroup) {
		return;
	}
	
	if (!$entity->isToolEnabled('forum')) {
		return;
	}
	
	$url = "discussion/group/{$entity->guid}";
	$item = new ElggMenuItem('discussion', elgg_echo('discussion:group'), $url);
	$return[] = $item;
	
	return $return;
}

/**
 * Prepare a notification message about a new discussion topic
 *
 * @param string                          $hook         Hook name
 * @param string                          $type         Hook type
 * @param Elgg\Notifications\Notification $notification The notification to prepare
 * @param array                           $params       Hook parameters
 *
 * @return Elgg\Notifications\Notification
 */
function discussion_prepare_notification($hook, $type, $notification, $params) {
	$entity = $params['event']->getObject();
	$owner = $params['event']->getActor();
	$language = $params['language'];

	$descr = $entity->description;
	$title = $entity->title;

	$notification->subject = elgg_echo('discussion:topic:notify:subject', [$title], $language);
	$notification->body = elgg_echo('discussion:topic:notify:body', [
		$owner->name,
		$title,
		$descr,
		$entity->getURL()
	], $language);
	$notification->summary = elgg_echo('discussion:topic:notify:summary', [$entity->title], $language);
	$notification->url = $entity->getURL();
	
	return $notification;
}

/**
 * Prepare a notification message about a new comment on a discussion
 *
 * @param string                          $hook         Hook name
 * @param string                          $type         Hook type
 * @param Elgg\Notifications\Notification $notification The notification to prepare
 * @param array                           $params       Hook parameters
 *
 * @return void|Elgg\Notifications\Notification
 */
function discussion_prepare_comment_notification($hook, $type, $notification, $params) {
	
	$event = elgg_extract('event', $params);
	if (!$event instanceof Elgg\Notifications\NotificationEvent) {
		return;
	}
	
	$comment = $event->getObject();
	if (!$comment instanceof ElggComment) {
		return;
	}
	
	$discussion = $comment->getContainerEntity();
	if (!elgg_instanceof($discussion, 'object', 'discussion')) {
		return;
	}
	
	$language = elgg_extract('language', $params);
	
	$poster = $comment->getOwnerEntity();
	
	$notification->subject = elgg_echo('discussion:comment:notify:subject', [$discussion->getDisplayName()], $language);
	$notification->summary = elgg_echo('discussion:comment:notify:summary', [$discussion->getDisplayName()], $language);
	$notification->body = elgg_echo('discussion:comment:notify:body', [
		$poster->getDisplayName(),
		$discussion->getDisplayName(),
		$comment->description,
		$comment->getURL(),
	], $language);
	$notification->url = $comment->getURL();
	
	return $notification;
}

/**
 * Add group members to the comment subscriber on a discussion
 *
 * @param string $hook          'get'
 * @param string $type          'subscriptions'
 * @param array  $subscriptions Array containing subscriptions in the form
 *                              <user guid> => array('email', 'site', etc.)
 * @param array  $params        Hook parameters
 *
 * @return void|array
 */
function discussion_get_subscriptions($hook, $type, $subscriptions, $params) {
	
	$event = elgg_extract('event', $params);
	if (!$event instanceof Elgg\Notifications\NotificationEvent) {
		return;
	}
	
	$comment = $event->getObject();
	if (!$comment instanceof ElggComment) {
		return;
	}
	
	$discussion = $comment->getContainerEntity();
	if (!elgg_instanceof($discussion, 'object', 'discussion')) {
		return;
	}
	
	$container = $discussion->getContainerEntity();
	if (!$container instanceof ElggGroup) {
		return;
	}
	
	$group_subscriptions = elgg_get_subscriptions_for_container($container->guid);
	
	return ($subscriptions + $group_subscriptions);
}

/**
 * Make sure that discussion comments can not be written to a discussion after it has been closed
 *
 * @param string $hook   'container_logic_check'
 * @param string $type   'object'
 * @param array  $return Allowed or not
 * @param array  $params Hook params
 *
 * @return void|false
 */
function discussion_comment_permissions($hook, $type, $return, $params) {
	
	$discussion = elgg_extract('entity', $params);
	if (!elgg_instanceof($discussion, 'object', 'discussion')) {
		return;
	}

	if ($discussion->status == 'closed') {
		// do not allow new comments in closed discussions
		return false;
	}
}

/**
 * Prepare discussion topic form variables
 *
 * @param ElggObject $topic Topic object if editing
 * @return array
 */
function discussion_prepare_form_vars($topic = null) {
	// input names => defaults
	$values = [
		'title' => '',
		'description' => '',
		'status' => '',
		'access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'topic' => $topic,
		'entity' => $topic,
	];

	if ($topic) {
		foreach (array_keys($values) as $field) {
			if (isset($topic->$field)) {
				$values[$field] = $topic->$field;
			}
		}
	}

	if (elgg_is_sticky_form('topic')) {
		$sticky_values = elgg_get_sticky_values('topic');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('topic');

	return $values;
}

/**
 * Add latest discussions tab to /groups/all page
 *
 * @param string         $hook   "register"
 * @param string         $type   "menu:filter:groups/all"
 * @param ElggMenuItem[] $return Menu
 * @param array          $params Hook params
 * @return ElggMenuItem[]
 */
function discussion_setup_groups_filter_tabs($hook, $type, $return, $params) {

	$filter_value = elgg_extract('filter_value', $params);

	$return[] = ElggMenuItem::factory([
		'name' => 'discussion',
		'text' => elgg_echo('discussion:latest'),
		'href' => 'groups/all?filter=discussion',
		'priority' => 500,
		'selected' => $filter_value == 'discussion',
	]);

	return $return;
}

return function() {
	elgg_register_event_handler('init', 'system', 'discussion_init');
};
