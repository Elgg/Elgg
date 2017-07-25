<?php

function elgg_friends_plugin_init() {
	elgg_register_plugin_hook_handler('access:collections:write', 'user', '_elgg_friends_write_access', 1);
	elgg_register_plugin_hook_handler('filter_tabs', 'all', '_elgg_friends_filter_tabs', 1);

	elgg_register_event_handler('create', 'relationship', '_elgg_send_friend_notification');

	elgg_register_page_handler('friends', '_elgg_friends_page_handler');
	elgg_register_page_handler('friendsof', '_elgg_friends_page_handler');

	elgg_register_plugin_hook_handler('entity:url', 'object', '_elgg_friends_widget_urls');
	
	elgg_register_plugin_hook_handler('register', 'menu:page', '_elgg_friends_page_menu');
	elgg_register_plugin_hook_handler('register', 'menu:topbar', '_elgg_friends_topbar_menu');
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', '_elgg_friends_setup_user_hover_menu');
}

elgg_register_event_handler('init', 'system', 'elgg_friends_plugin_init');

/**
 * Adds friending to user hover menu
 *
 * @access private
 */
function _elgg_friends_setup_user_hover_menu($hook, $type, $return, $params) {
	$user = $params['entity'];
	/* @var \ElggUser $user */

	if (elgg_is_logged_in()) {
		if (elgg_get_logged_in_user_guid() != $user->guid) {
			$isFriend = $user->isFriend();

			// Always emit both to make it super easy to toggle with ajax
			$return[] = \ElggMenuItem::factory([
				'name' => 'remove_friend',
				'href' => "action/friends/remove?friend={$user->guid}",
				'is_action' => true,
				'text' => elgg_echo('friend:remove'),
				'icon' => 'user-times',
				'section' => 'action',
				'item_class' => $isFriend ? '' : 'hidden',
			]);

			$return[] = \ElggMenuItem::factory([
				'name' => 'add_friend',
				'href' => "action/friends/add?friend={$user->guid}",
				'is_action' => true,
				'text' => elgg_echo('friend:add'),
				'icon' => 'user-plus',
				'section' => 'action',
				'item_class' => $isFriend ? 'hidden' : '',
			]);
		}
	}

	return $return;
}

/**
 * Page handler for friends-related pages
 *
 * @param array  $segments URL segments
 * @param string $handler  The first segment in URL used for routing
 *
 * @return bool
 * @access private
 */
function _elgg_friends_page_handler($segments, $handler) {
	elgg_set_context('friends');

	if (isset($segments[0]) && $user = get_user_by_username($segments[0])) {
		elgg_set_page_owner_guid($user->getGUID());
	}

	if (!elgg_get_page_owner_guid()) {
		return false;
	}

	switch ($handler) {
		case 'friends':
			echo elgg_view_resource("friends/index");
			break;
		case 'friendsof':
			echo elgg_view_resource("friends/of");
			break;
		default:
			return false;
	}
	return true;
}

/**
 * Register menu items for the topbar menu
 *
 * @param string $hook
 * @param string $type
 * @param array  $return
 * @param array  $params
 * @return array
 *
 * @access private
 *
 * @since 3.0
 */
function _elgg_friends_topbar_menu($hook, $type, $return, $params) {

	$viewer = elgg_get_logged_in_user_entity();
	if (!$viewer) {
		return;
	}
		
	$return[] = \ElggMenuItem::factory([
		'name' => 'friends',
		'href' => "friends/{$viewer->username}",
		'text' => elgg_echo('friends'),
		'icon' => 'users',
		'title' => elgg_echo('friends'),
		'priority' => 300,
	]);
	
	return $return;
}

/**
 * Register menu items for the friends page menu
 *
 * @param string $hook
 * @param string $type
 * @param array  $return
 * @param array  $params
 * @return array
 *
 * @access private
 *
 * @since 3.0
 */
function _elgg_friends_page_menu($hook, $type, $return, $params) {

	$owner = elgg_get_page_owner_entity();
	if (!$owner instanceof ElggUser) {
		return;
	}

	$return[] = \ElggMenuItem::factory([
		'name' => 'friends',
		'text' => elgg_echo('friends'),
		'href' => 'friends/' . $owner->username,
		'contexts' => ['friends'],
	]);

	$return[] = \ElggMenuItem::factory([
		'name' => 'friends:of',
		'text' => elgg_echo('friends:of'),
		'href' => 'friendsof/' . $owner->username,
		'contexts' => ['friends'],
	]);

	return $return;
}

/**
 * Notify user that someone has friended them
 *
 * @param string           $event  Event name
 * @param string           $type   Object type
 * @param \ElggRelationship $object Object
 *
 * @return bool
 * @access private
 */
function _elgg_send_friend_notification($event, $type, $object) {
	if ($object->relationship != 'friend') {
		return true;
	}

	$user_one = get_entity($object->guid_one);
	/* @var \ElggUser $user_one */

	$user_two = get_entity($object->guid_two);
	/* @var ElggUser $user_two */

	// Notification subject
	$subject = elgg_echo('friend:newfriend:subject', [
		$user_one->name
	], $user_two->language);

	// Notification body
	$body = elgg_echo("friend:newfriend:body", [
		$user_one->name,
		$user_one->getURL()
	], $user_two->language);

	// Notification params
	$params = [
		'action' => 'add_friend',
		'object' => $user_one,
		'friend' => $user_two,
		'url' => $user_two->getURL(),
	];

	return notify_user($user_two->guid, $object->guid_one, $subject, $body, $params);
}

/**
 * Add ACCESS_FRIENDS to the available access levels
 *
 * @param string $hook         "access:collections:write"
 * @param string $type         "user"
 * @param array  $access_array Access array
 * @param array  $params       Hook params
 *
 * @return array
 */
function _elgg_friends_write_access($hook, $type, $access_array, $params) {

	// rebuild array, putting friends 1st or 2nd
	$ret = [];

	// private exists, it goes first
	if (isset($access_array[ACCESS_PRIVATE])) {
		$ret[ACCESS_PRIVATE] = $access_array[ACCESS_PRIVATE];
		unset($access_array[ACCESS_PRIVATE]);
	}

	// friends
	$ret[ACCESS_FRIENDS] = get_readable_access_level(ACCESS_FRIENDS);

	// rest
	foreach ($access_array as $key => $value) {
		$ret[$key] = $value;
	}

	return $ret;
}

/**
 * Add "Friends" tab to common filter
 *
 * @param string $hook   "filter_tabs"
 * @param string $type   Context
 * @param array  $items  Menu items to render as tabs
 * @param array  $params Hook params
 *
 * @return array
 */
function _elgg_friends_filter_tabs($hook, $type, $items, $params) {

	$user = elgg_extract('user', $params);
	if (!$user instanceof ElggUser) {
		return;
	}

	$vars = elgg_extract('vars', $params);
	$selected = elgg_extract('selected', $params);

	$items[] = ElggMenuItem::factory([
		'name' => 'friend',
		'text' => elgg_echo('friends'),
		'href' => (isset($vars['friend_link'])) ? $vars['friend_link'] : "$type/friends/{$user->username}",
		'selected' => ($selected == 'friends'),
		'priority' => 400,
	]);
	return $items;
}


/**
 * Returns widget URLS used in widget titles
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param string $result URL
 * @param array  $params Parameters
 * @return string|null
 * @access private
 */
function _elgg_friends_widget_urls($hook, $type, $result, $params) {
	$widget = elgg_extract('entity', $params);
	if (!($widget instanceof \ElggWidget)) {
		return;
	}
	
	if ($widget->handler !== 'friends') {
		return;
	}
	
	$owner = $widget->getOwnerEntity();
	if (!($owner instanceof \ElggUser)) {
		return;
	}
			
	return "friends/{$owner->username}";
}
