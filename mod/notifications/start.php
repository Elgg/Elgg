<?php
/**
 * Elgg notifications plugin
 *
 * @package ElggNotifications
 */

elgg_register_event_handler('init', 'system', 'notifications_plugin_init');

function notifications_plugin_init() {

	elgg_extend_view('elgg.css','notifications/css');

	elgg_register_page_handler('notifications', 'notifications_page_handler');

	elgg_register_event_handler('pagesetup', 'system', 'notifications_plugin_pagesetup');

	// Unset the default notification settings
	elgg_unregister_plugin_hook_handler('usersettings:save', 'user', '_elgg_save_notification_user_settings');
	elgg_unextend_view('forms/account/settings', 'core/settings/account/notifications');

	// update notifications based on relationships changing
	elgg_register_event_handler('delete', 'relationship', 'notifications_relationship_remove');

	// update notifications when new friend or access collection membership
	elgg_register_event_handler('create', 'relationship', 'notifications_update_friend_notify');
	elgg_register_plugin_hook_handler('access:collections:add_user', 'collection', 'notifications_update_collection_notify');

	$actions_base = __DIR__ . '/actions';
	elgg_register_action("notificationsettings/save", "$actions_base/save.php");
	elgg_register_action("notificationsettings/groupsave", "$actions_base/groupsave.php");

	// register unit tests
	elgg_register_plugin_hook_handler('unit_test', 'system', 'notifications_register_tests');
}

/**
 * Route page requests
 *
 * @param array $page Array of url parameters
 * @return bool
 */
function notifications_page_handler($page) {

	elgg_gatekeeper();

	// Set the context to settings
	elgg_set_context('settings');

	$current_user = elgg_get_logged_in_user_entity();

	// default to personal notifications
	if (!isset($page[0])) {
		$page[0] = 'personal';
	}
	if (!isset($page[1])) {
		forward("notifications/{$page[0]}/{$current_user->username}");
	}

	$vars['username'] = $page[1];

	// note: $user passed in
	switch ($page[0]) {
		case 'group':
			echo elgg_view_resource('notifications/groups', $vars);
			break;
		case 'personal':
			echo elgg_view_resource('notifications/index', $vars);
			break;
		default:
			return false;
	}
	return true;
}

/**
 * Notification settings sidebar menu
 *
 */
function notifications_plugin_pagesetup() {
	if (elgg_in_context("settings") && elgg_get_logged_in_user_guid()) {

		$user = elgg_get_page_owner_entity();
		if (!$user) {
			$user = elgg_get_logged_in_user_entity();
		}

		$params = array(
			'name' => '2_a_user_notify',
			'text' => elgg_echo('notifications:subscriptions:changesettings'),
			'href' => "notifications/personal/{$user->username}",
			'section' => "notifications",
		);
		elgg_register_menu_item('page', $params);
		
		if (elgg_is_active_plugin('groups')) {
			$params = array(
				'name' => '2_group_notify',
				'text' => elgg_echo('notifications:subscriptions:changesettings:groups'),
				'href' => "notifications/group/{$user->username}",
				'section' => "notifications",
			);
			elgg_register_menu_item('page', $params);
		}
	}
}

/**
 * Update notifications when a relationship is deleted
 *
 * @param string            $event        "delete"
 * @param string            $object_type  "relationship"
 * @param \ElggRelationship $relationship Relationship obj
 * @return void
 */
function notifications_relationship_remove($event, $object_type, $relationship) {
	
	if (!in_array($relationship->relationship, ['member', 'friend'])) {
		return;
	}
	
	$methods = array_keys(_elgg_services()->notifications->getMethodsAsDeprecatedGlobal());
	foreach($methods as $method) {
		elgg_remove_subscription($relationship->guid_one, $method, $relationship->guid_two);
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
	// The handler gets triggered regardless of which relationship was
	// created, so proceed only if dealing with a 'friend' relationship.
	if ($relationship->relationship != 'friend') {
		return true;
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
	$NOTIFICATION_HANDLERS = _elgg_services()->notifications->getMethodsAsDeprecatedGlobal();

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

/**
 * Register unit tests
 * 
 * @param string   $hook  "unit_test"
 * @param string   $type  "system"
 * @param string[] $tests Tests
 * @return string[]
 */
function notifications_register_tests($hook, $type, $tests) {
	$tests[] = __DIR__ . '/tests/ElggNotificationsPluginUnitTest.php';
	return $tests;
}