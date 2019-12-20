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

	// prevent comments on closed discussions
	elgg_register_plugin_hook_handler('permissions_check:comment', 'object', 'discussion_comment_permissions');

	// add link to owner block
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'discussion_owner_block_menu');

	// add the forum tool option
	elgg()->group_tools->register('forum');

	// notifications
	elgg_register_plugin_hook_handler('get', 'subscriptions', 'discussion_get_subscriptions');
	elgg_register_notification_event('object', 'discussion');
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:discussion', 'discussion_prepare_notification');
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:comment', 'discussion_prepare_comment_notification');

	// allow to be liked
	elgg_register_plugin_hook_handler('likes:is_likable', 'object:discussion', 'Elgg\Values::getTrue');

	// Add latest discussions tab to /groups/all page
	elgg_register_plugin_hook_handler('register', 'menu:filter:groups/all', 'discussion_setup_groups_filter_tabs');

	elgg_register_plugin_hook_handler('register', 'menu:site', 'Elgg\Discussions\Menus::registerSiteMenuItem');

	// register database seed
	elgg_register_plugin_hook_handler('seeds', 'database', 'discussion_register_db_seeds');

	elgg_register_plugin_hook_handler('container_logic_check', 'object', 'Elgg\Discussions\Permissions::containerLogic');
}

/**
 * Add owner block link for groups
 *
 * @param \Elgg\Hook $hook 'register', 'menu:owner_block'
 *
 * @return void|ElggMenuItem[]
 */
function discussion_owner_block_menu(\Elgg\Hook $hook) {
	
	$entity = $hook->getEntityParam();
	if (!$entity instanceof ElggGroup) {
		return;
	}
	
	if (!$entity->isToolEnabled('forum')) {
		return;
	}
	
	$url = elgg_generate_url('collection:object:discussion:group', [
		'guid' => $entity->guid,
	]);
	
	$return = $hook->getValue();
	$return[] = new ElggMenuItem('discussion', elgg_echo('collection:object:discussion:group'), $url);
	
	return $return;
}

/**
 * Prepare a notification message about a new discussion topic
 *
 * @param \Elgg\Hook $hook 'prepare', 'notification:create:object:discussion'
 *
 * @return Elgg\Notifications\Notification
 */
function discussion_prepare_notification(\Elgg\Hook $hook) {
	$entity = $hook->getParam('event')->getObject();
	$owner = $hook->getParam('event')->getActor();
	$language = $hook->getParam('language');

	$title = $entity->getDisplayName();

	$notification = $hook->getValue();
	$notification->subject = elgg_echo('discussion:topic:notify:subject', [$title], $language);
	$notification->body = elgg_echo('discussion:topic:notify:body', [
		$owner->getDisplayName(),
		$title,
		$entity->description,
		$entity->getURL()
	], $language);
	$notification->summary = elgg_echo('discussion:topic:notify:summary', [$title], $language);
	$notification->url = $entity->getURL();
	
	return $notification;
}

/**
 * Prepare a notification message about a new comment on a discussion
 *
 * @param \Elgg\Hook $hook 'prepare', 'notification:create:object:comment'
 *
 * @return void|Elgg\Notifications\Notification
 */
function discussion_prepare_comment_notification(\Elgg\Hook $hook) {
	
	$event = $hook->getParam('event');
	if (!$event instanceof Elgg\Notifications\NotificationEvent) {
		return;
	}
	
	$comment = $event->getObject();
	if (!$comment instanceof ElggComment) {
		return;
	}
	
	$discussion = $comment->getContainerEntity();
	if (!$discussion instanceof ElggDiscussion) {
		return;
	}
	
	$language = $hook->getParam('language');
	
	$poster = $comment->getOwnerEntity();
	
	$notification = $hook->getValue();
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
 * @param \Elgg\Hook $hook 'get', 'subscriptions'
 *
 * @return void|array
 */
function discussion_get_subscriptions(\Elgg\Hook $hook) {
	
	$event = $hook->getParam('event');
	if (!$event instanceof \Elgg\Notifications\SubscriptionNotificationEvent) {
		return;
	}
	
	if ($event->getAction() !== 'create') {
		return;
	}
	
	$comment = $event->getObject();
	if (!$comment instanceof ElggComment) {
		return;
	}
	
	$discussion = $comment->getContainerEntity();
	if (!$discussion instanceof ElggDiscussion) {
		return;
	}
	
	$container = $discussion->getContainerEntity();
	if (!$container instanceof ElggGroup) {
		return;
	}
	
	$subscriptions = $hook->getValue();
	$group_subscriptions = elgg_get_subscriptions_for_container($container->guid);
	
	return ($subscriptions + $group_subscriptions);
}

/**
 * Make sure that discussion comments can not be written to a discussion after it has been closed
 *
 * @param \Elgg\Hook $hook 'container_logic_check', 'object'
 *
 * @return void|false
 */
function discussion_comment_permissions(\Elgg\Hook $hook) {
	
	$discussion = $hook->getEntityParam();
	if (!$discussion instanceof ElggDiscussion) {
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
 * @param \Elgg\Hook $hook "register", "menu:filter:groups/all"
 *
 * @return ElggMenuItem[]
 */
function discussion_setup_groups_filter_tabs(\Elgg\Hook $hook) {
	$return = $hook->getValue();
	$return[] = ElggMenuItem::factory([
		'name' => 'discussion',
		'text' => elgg_echo('discussion:latest'),
		'href' => elgg_generate_url('collection:group:group:all', [
			'filter' => 'discussion',
		]),
		'priority' => 500,
	]);

	return $return;
}

/**
 * Register database seed
 *
 * @elgg_plugin_hook seeds database
 *
 * @param \Elgg\Hook $hook Hook
 * @return array
 */
function discussion_register_db_seeds(\Elgg\Hook $hook) {

	$seeds = $hook->getValue();

	$seeds[] = \Elgg\Discussions\Seeder::class;

	return $seeds;
}

return function() {
	elgg_register_event_handler('init', 'system', 'discussion_init');
};
