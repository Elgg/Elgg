<?php

/**
 * Elgg notifications plugin
 *
 * @package ElggNotifications
 */


function notifications_plugin_init() {

	elgg_extend_view('css/elgg','notifications/css');

	elgg_register_page_handler('notifications', 'notifications_page_handler');

	elgg_register_event_handler('pagesetup', 'system', 'notifications_plugin_pagesetup');

	// Unset the default notification settings
	elgg_unregister_plugin_hook_handler('usersettings:save', 'user', 'notification_user_settings_save');
	elgg_unextend_view('forms/account/settings', 'core/settings/account/notifications');

	// update notifications based on relationships changing
	elgg_register_event_handler('delete', 'member', 'notifications_relationship_remove');
	elgg_register_event_handler('delete', 'friend', 'notifications_relationship_remove');

	// update notifications when new friend or access collection membership
	elgg_register_event_handler('create', 'friend', 'notifications_update_friend_notify');
	elgg_register_plugin_hook_handler('access:collections:add-user', 'collection', 'notifications_update_collection_notify');

	$actions_base = elgg_get_plugins_path() . 'notifications/actions';
	elgg_register_action("notificationsettings/save", "$actions_base/save.php");
	elgg_register_action("notificationsettings/groupsave", "$actions_base/groupsave.php");
}

/**
 * Route page requests
 *
 * @param array $page Array of url parameters
 */
function notifications_page_handler($page) {

	// default to personal notifications
	if (!isset($page[0])) {
		$page[0] = 'personal';
	}

	$base = elgg_get_plugins_path() . 'notifications';

	switch ($page[0]) {
		case 'group':
			require "$base/groups.php";
			break;
		case 'personal':
		default:
			require "$base/index.php";
			break;
	}

	return TRUE;
}

/**
 * Notification settings sidebar menu
 *
 */
function notifications_plugin_pagesetup() {
	if (elgg_get_context() == "settings" && elgg_get_logged_in_user_guid()) {
		$user = elgg_get_logged_in_user_entity();

		$params = array(
			'name' => '2_a_user_notify',
			'text' => elgg_echo('notifications:subscriptions:changesettings'),
			'href' => "notifications/personal",
		);
		elgg_register_menu_item('page', $params);
		
		if (elgg_is_active_plugin('groups')) {
			$params = array(
				'name' => '2_group_notify',
				'text' => elgg_echo('notifications:subscriptions:changesettings:groups'),
				'href' => "notifications/group",
			);
			elgg_register_menu_item('page', $params);
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
			if ($event == 'access:collections:add-user') {
				add_entity_relationship($user->guid, "notify$method", $member_guid);
			} elseif ($event == 'access:collections:remove_user') {
				// removing someone from an access collection is not a guarantee
				// that they should be removed from notifications
				//remove_entity_relationship($user->guid, "notify$method", $member_guid);
			}
		}
	}
}

elgg_register_event_handler('init', 'system', 'notifications_plugin_init', 1000);
