<?php

/**
 * Elgg notifications plugin
 *
 * @package ElggNotifications
 */


function notifications_plugin_init() {
	global $CONFIG;

	elgg_extend_view('css','notifications/css');

	register_page_handler('notifications', 'notifications_page_handler');

	register_elgg_event_handler('pagesetup', 'system', 'notifications_plugin_pagesetup');

	// Unset the default notification settings
	unregister_plugin_hook('usersettings:save', 'user', 'notification_user_settings_save');
	elgg_unextend_view('usersettings/user', 'notifications/settings/usersettings');

	// update notifications based on relationships changing
	register_elgg_event_handler('delete', 'member', 'notifications_relationship_remove');
	register_elgg_event_handler('delete', 'friend', 'notifications_relationship_remove');

	// update notifications when new friend or access collection membership
	register_elgg_event_handler('create', 'friend', 'notifications_update_friend_notify');
	register_plugin_hook('access:collections:add_user', 'collection', 'notifications_update_collection_notify');
}

/**
 * Route page requests
 *
 * @param array $page Array of url parameters
 */
function notifications_page_handler($page) {
	global $CONFIG;

	// default to personal notifications
	if (!isset($page[0])) {
		$page[0] = 'personal';
	}

	switch ($page[0]) {
		case 'group':
			require $CONFIG->pluginspath . "notifications/groups.php";
			break;
		case 'personal':
		default:
			require $CONFIG->pluginspath . "notifications/index.php";
			break;
	}

	return TRUE;
}

/**
 * Notification settings sidebar menu
 *
 */
function notifications_plugin_pagesetup() {
	global $CONFIG;
	if (get_context() == 'settings') {
		add_submenu_item(elgg_echo('notifications:subscriptions:changesettings'), $CONFIG->wwwroot . "pg/notifications/personal");
		if (is_plugin_enabled('groups')) {
			add_submenu_item(elgg_echo('notifications:subscriptions:changesettings:groups'), $CONFIG->wwwroot . "pg/notifications/group");
		}
	}
}

/**
 * Update notifications when a relationship is deleted
 *
 * @param string $event
 * @param string $object_type
 * @param object $relationship
 */
function notifications_relationship_remove($event, $object_type, $relationship) {
	global $NOTIFICATION_HANDLERS;

	$user_guid = $relationship->guid_one;
	$object_guid = $relationship->guid_two;

	// loop through all notification types
	foreach($NOTIFICATION_HANDLERS as $method => $foo) {
		remove_entity_relationship($user_guid, "notify{$method}", $object_guid);
	}
}

/**
 * Turn on notifications for new friends if all friend notifications is on
 *
 * @param string $event
 * @param string $object_type
 * @param object $relationship
 */
function notifications_update_friend_notify($event, $object_type, $relationship) {
	global $NOTIFICATION_HANDLERS;

	$user_guid = $relationship->guid_one;
	$friend_guid = $relationship->guid_two;

	$user = get_entity($user_guid);

	// loop through all notification types
	foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
		$metaname = 'collections_notifications_preferences_' . $method;
		$collections_preferences = $user->$metaname;
		if ($collections_preferences) {
			if (!empty($collections_preferences) && !is_array($collections_preferences)) {
				$collections_preferences = array($collections_preferences);
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
 * @param string $event
 * @param string $object_type
 * @param bool $returnvalue
 * @param array $params
 */
function notifications_update_collection_notify($event, $object_type, $returnvalue, $params) {
	global $NOTIFICATION_HANDLERS;

	// only update notifications for user owned collections
	$collection_id = $params['collection_id'];
	$collection = get_access_collection($collection_id);
	$user = get_entity($collection->owner_guid);
	if (!($user instanceof ElggUser)) {
		return $returnvalue;
	}

	$member_guid = $params['user_guid'];

	// loop through all notification types
	foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
		$metaname = 'collections_notifications_preferences_' . $method;
		$collections_preferences = $user->$metaname;
		if (!$collections_preferences) {
			continue;
		}
		if (!is_array($collections_preferences)) {
			$collections_preferences = array($collections_preferences);
		}
		if (in_array(-1, $collections_preferences)) {
			// if "all friends" notify is on, we don't change any notifications
			// since must be a friend to be in an access collection
			continue;
		}
		if (in_array($collection_id, $collections_preferences)) {
			// notifications are on for this collection so we add/remove
			if ($event == 'access:collections:add_user') {
				add_entity_relationship($user->guid, "notify$method", $member_guid);
			} elseif ($event == 'access:collections:remove_user') {
				// removing someone from an access collection is not a guarantee
				// that they should be removed from notifications
				//remove_entity_relationship($user->guid, "notify$method", $member_guid);
			}
		}
	}
}

register_elgg_event_handler('init', 'system', 'notifications_plugin_init', 1000);


register_action("notificationsettings/save", FALSE, $CONFIG->pluginspath . "notifications/actions/save.php");
register_action("notificationsettings/groupsave", FALSE, $CONFIG->pluginspath . "notifications/actions/groupsave.php");
