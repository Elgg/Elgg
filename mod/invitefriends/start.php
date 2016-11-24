<?php
/**
 * Elgg invite friends
 *
 * @package ElggInviteFriends
 */

elgg_register_event_handler('init', 'system', 'invitefriends_init');

function invitefriends_init() {
	elgg_register_page_handler('invite', 'invitefriends_page_handler');

	elgg_register_plugin_hook_handler('register', 'user', 'invitefriends_add_friends');

	elgg_register_plugin_hook_handler('register', 'menu:page', 'invitefriends_register_page_menu');
}

/**
 * Page handler function
 *
 * @param array $page Page URL segments
 * @return bool
 */
function invitefriends_page_handler($page) {
	echo elgg_view_resource('invitefriends/invite');
	return true;
}

/**
 * Adds menu items to the page menu
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param array  $result Current items
 * @param array  $params Hook params
 * @return null|array
 */
function invitefriends_register_page_menu($hook, $type, $result, $params) {
	if (!elgg_is_logged_in()) {
		return;
	}
	
	if (!elgg_get_config('allow_registration')) {
		return;
	}
	
	$result[] = \ElggMenuItem::factory([
		'name' => 'invite',
		'text' => elgg_echo('friends:invite'),
		'href' => 'invite',
		'contexts' => ['friends'],
	]);
	
	return $result;
}

/**
 * Add friends if invite code was set
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param bool   $result Whether to allow registration
 * @param array  $params Hook params
 * @return void
 */
function invitefriends_add_friends($hook, $type, $result, $params) {
	$user = elgg_extract('user', $params);
	if (!($user instanceof \ElggUser)) {
		return;
	}
	
	$friend_guid = elgg_extract('friend_guid', $params);
	$invite_code = elgg_extract('invitecode', $params);
	
	if (!$friend_guid) {
		return;
	}
	
	$friend_user = get_user($friend_guid);
	if (!($friend_user instanceof \ElggUser)) {
		return;
	}

	if (!elgg_validate_invite_code($friend_user->username, $invite_code)) {
		return;
	}
	
	// Make mutual friends
	$user->addFriend($friend_guid, true);
	$friend_user->addFriend($user->guid, true);
}
