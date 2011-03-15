<?php

/**
 * Elgg invite friends
 *
 * @package ElggInviteFriends
 */

register_elgg_event_handler('init', 'system', 'invitefriends_init');

/**
 * Invite friends initialization
 */
function invitefriends_init() {
	global $CONFIG;

	register_page_handler('invitefriends', 'invitefriends_page_handler');

	register_elgg_event_handler('pagesetup', 'system', 'invitefriends_menu_setup');

	$action_base = $CONFIG->pluginspath . 'invitefriends/actions';
	register_action('invitefriends/invite', false, "$action_base/invite.php");
}

/**
 * Load the invite friends page
 */
function invitefriends_page_handler() {
	global $CONFIG;
	require "{$CONFIG->pluginspath}invitefriends/index.php";
}

/**
 * Add menu item for invite friends
 */
function invitefriends_menu_setup() {
	global $CONFIG;
	if (isloggedin()) {
		$context = get_context();
		if ($context == "friends" || $context == "friendsof" || $context == "collections") {
			$url = "{$CONFIG->wwwroot}pg/invitefriends/";
			add_submenu_item(elgg_echo('friends:invite'), $url, 'invite');
		}
	}
}
