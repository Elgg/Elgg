<?php
/**
 * Elgg invite friends
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
 * @param \Elgg\Hook $hook 'register', 'menu:page'
 *
 * @return void|ElggMenuItem[]
 */
function invitefriends_register_page_menu(\Elgg\Hook $hook) {
	
	$user = elgg_get_logged_in_user_entity();
	if (!$user instanceof \ElggUser) {
		return;
	}
	
	if (!elgg_get_config('allow_registration')) {
		return;
	}
	
	$result = $hook->getValue();
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
 * @param \Elgg\Hook $hook 'register', 'user'
 *
 * @return void
 */
function invitefriends_add_friends(\Elgg\Hook $hook) {
	$user = $hook->getUserParam();
	if (!$user instanceof \ElggUser) {
		return;
	}
	
	$friend_guid = $hook->getParam('friend_guid');
	$invite_code = $hook->getParam('invitecode');
	
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
