<?php
/**
 * Elgg notifications plugin
 */

/**
 * Notifications init
 *
 * @return void
 */
function notifications_plugin_init() {

	elgg_extend_view('elgg.css', 'notifications.css');

	elgg_register_plugin_hook_handler('register', 'menu:title', '_notification_groups_title_menu');

	elgg_register_plugin_hook_handler('register', 'menu:page', '_notifications_page_menu');

	// Unset the default notification settings
	elgg_unregister_plugin_hook_handler('usersettings:save', 'user', '_elgg_save_notification_user_settings');
	elgg_unextend_view('forms/usersettings/save', 'core/settings/account/notifications');
	
	// update notifications based on relationships changing
	elgg_register_event_handler('delete', 'relationship', 'notifications_relationship_remove');

	// update notifications when new friend or access collection membership
	elgg_register_event_handler('create', 'relationship', 'notifications_update_friend_notify');
	elgg_register_plugin_hook_handler('access:collections:add_user', 'collection', 'notifications_update_collection_notify');
}

/**
 * Register menu items for the page menu
 *
 * @param \Elgg\Hook $hook 'register', 'menu:page'
 *
 * @return void|ElggMenuItem[]
 *
 * @internal
 * @since 3.0
 */
function _notifications_page_menu(\Elgg\Hook $hook) {
	
	if (!elgg_in_context('settings') || !elgg_get_logged_in_user_guid()) {
		return;
	}

	$user = elgg_get_page_owner_entity();
	if (!$user) {
		$user = elgg_get_logged_in_user_entity();
	}
	
	$return = $hook->getValue();
	$return[] = \ElggMenuItem::factory([
		'name' => '2_a_user_notify',
		'text' => elgg_echo('notifications:subscriptions:changesettings'),
		'href' => elgg_generate_url('settings:notification:personal', [
			'username' => $user->username,
		]),
		'section' => 'notifications',
	]);
	
	if (elgg_is_active_plugin('groups')) {
		$return[] = \ElggMenuItem::factory([
			'name' => '2_group_notify',
			'text' => elgg_echo('notifications:subscriptions:changesettings:groups'),
			'href' => elgg_generate_url('settings:notification:groups', [
				'username' => $user->username,
			]),
			'section' => 'notifications',
		]);
	}
		
	return $return;
}

/**
 * Register menu items for the title menu on group profiles
 *
 * @param \Elgg\Hook $hook 'register' 'menu:title'
 *
 * @return void
 *
 * @internal
 * @since 3.0
 */
function _notification_groups_title_menu(\Elgg\Hook $hook) {
	if (!elgg_is_active_plugin('groups')) {
		return;
	}

	$user = elgg_get_logged_in_user_entity();
	if (!$user) {
		return;
	}

	$items = $hook->getValue();
	
	$group = $hook->getEntityParam();
	if (!$group instanceof \ElggGroup || !$group->isMember($user)) {
		return;
	}
	
	$subscribed = false;
	$methods = elgg_get_notification_methods();
	foreach ($methods as $method) {
		$subscribed = check_entity_relationship($user->guid, 'notify' . $method, $group->guid);
		if ($subscribed) {
			break;
		}
	}
		
	$items[] = \ElggMenuItem::factory([
		'name' => 'notifications',
		'parent_name' => 'group-dropdown',
		'text' => elgg_echo('notifications:subscriptions:changesettings:groups'),
		'href' => elgg_generate_url('settings:notification:groups', [
			'username' => $user->username,
		]),
		'badge' => $subscribed ? elgg_echo('on') : elgg_echo('off'),
		'icon' => $subscribed ? 'bell' : 'bell-slash',
	]);
	
	return $items;
}

/**
 * Update notifications when a relationship is deleted
 *
 * @param \Elgg\Event $event "delete", "relationship"
 *
 * @return void
 */
function notifications_relationship_remove(\Elgg\Event $event) {
	$relationship = $event->getObject();
	if (!$relationship instanceof ElggRelationship) {
		return;
	}
	
	if (!in_array($relationship->relationship, ['member', 'friend'])) {
		return;
	}
	
	$methods = array_keys(_elgg_services()->notifications->getMethodsAsDeprecatedGlobal());
	foreach ($methods as $method) {
		elgg_remove_subscription($relationship->guid_one, $method, $relationship->guid_two);
	}
}

/**
 * Turn on notifications for new friends if all friend notifications is on
 *
 * @param \Elgg\Event $event 'create', 'relationship'
 *
 * @return void
 */
function notifications_update_friend_notify(\Elgg\Event $event) {
	$relationship = $event->getObject();
	if (!$relationship instanceof ElggRelationship) {
		return;
	}
	
	// The handler gets triggered regardless of which relationship was
	// created, so proceed only if dealing with a 'friend' relationship.
	if ($relationship->relationship != 'friend') {
		return;
	}

	$NOTIFICATION_HANDLERS = _elgg_services()->notifications->getMethodsAsDeprecatedGlobal();

	$user_guid = $relationship->guid_one;
	$friend_guid = $relationship->guid_two;

	$user = get_entity($user_guid);

	// loop through all notification types
	foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
		$metaname = 'collections_notifications_preferences_' . $method;
		$collections_preferences = $user->$metaname;
		if ($collections_preferences) {
			if (!empty($collections_preferences) && !is_array($collections_preferences)) {
				$collections_preferences = [$collections_preferences];
			}
			if (is_array($collections_preferences)) {
				// -1 means all friends is on - should be a define
				if (in_array(-1, $collections_preferences)) {
					add_entity_relationship($user_guid, 'notify' . $method, $friend_guid);
				}
			}
		}
	}
}

/**
 * Update notifications for changes in access collection membership.
 *
 * This function assumes that only friends can belong to access collections.
 *
 * @param \Elgg\hook $hook 'access:collections:add_user', 'collection'
 *
 * @return void
 */
function notifications_update_collection_notify(\Elgg\hook $hook) {
	$NOTIFICATION_HANDLERS = _elgg_services()->notifications->getMethodsAsDeprecatedGlobal();

	// only update notifications for user owned collections
	$collection_id = $hook->getParam('collection_id');
	$collection = get_access_collection($collection_id);
	if (!$collection instanceof ElggAccessCollection) {
		return;
	}
	$user = get_entity($collection->owner_guid);
	if (!($user instanceof ElggUser)) {
		return;
	}

	$member_guid = (int) $hook->getParam('user_guid');
	if (empty($member_guid)) {
		return;
	}

	// loop through all notification types
	foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
		$metaname = 'collections_notifications_preferences_' . $method;
		$collections_preferences = $user->$metaname;
		if (!$collections_preferences) {
			continue;
		}
		if (!is_array($collections_preferences)) {
			$collections_preferences = [$collections_preferences];
		}
		if (in_array(-1, $collections_preferences)) {
			// if "all friends" notify is on, we don't change any notifications
			// since must be a friend to be in an access collection
			continue;
		}
		if (in_array($collection_id, $collections_preferences)) {
			// notifications are on for this collection so we add/remove
			if ($hook->getName() == 'access:collections:add_user') {
				add_entity_relationship($user->guid, "notify$method", $member_guid);
			} elseif ($hook->getName() == 'access:collections:remove_user') {
				// removing someone from an access collection is not a guarantee
				// that they should be removed from notifications
				//remove_entity_relationship($user->guid, "notify$method", $member_guid);
			}
		}
	}
}

return function() {
	elgg_register_event_handler('init', 'system', 'notifications_plugin_init');
};
