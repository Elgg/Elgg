<?php
/**
 * Elgg invite friends
 *
 * @package ElggInviteFriends
 */

/**
 * Add menu item for invite friends
 */
function invitefriends_pagesetup() {
	global $CONFIG;
	if (elgg_get_context() == "friends" ||
		elgg_get_context() == "friendsof" ||
		elgg_get_context() == "collections") {
			add_submenu_item(elgg_echo('friends:invite'), "mod/invitefriends/",'invite');
	}
}

register_action('invitefriends/invite', false, $CONFIG->pluginspath . 'invitefriends/actions/invite.php');
elgg_register_event_handler('pagesetup', 'system', 'invitefriends_pagesetup');
