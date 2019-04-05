<?php
/**
 * Elgg invite friends
 *
 * @package ElggInviteFriends
 */

/**
 * Invite friend init
 *
 * @return void
 */
function invitefriends_init() {
	elgg_register_plugin_hook_handler('register', 'user', 'invitefriends_add_friends');

	elgg_register_plugin_hook_handler('register', 'menu:page', 'invitefriends_register_page_menu');
}

/**
 * Adds menu items to the page menu
 *
 * @param string         $hook   'register'
 * @param string         $type   'menu:page'
 * @param ElggMenuItem[] $result Current items
 * @param array          $params Hook params
 *
 * @return void|ElggMenuItem[]
 */
function invitefriends_register_page_menu($hook, $type, $result, $params) {
	
	$user = elgg_get_logged_in_user_entity();
	if (!$user instanceof \ElggUser) {
		return;
	}
	
	if (!elgg_get_config('allow_registration')) {
		return;
	}
	
	$result[] = \ElggMenuItem::factory([
		'name' => 'invite',
		'text' => elgg_echo('friends:invite'),
		'href' => elgg_generate_url('default:user:user:invite', [
			'username' => $user->username,
		]),
		'contexts' => ['friends'],
	]);
	
	return $result;
}

/**
 * Add friends if invite code was set
 *
 * @param string $hook   'register'
 * @param string $type   'user'
 * @param bool   $result Whether to allow registration
 * @param array  $params Hook params
 *
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

return function() {
	elgg_register_event_handler('init', 'system', 'invitefriends_init');
};
