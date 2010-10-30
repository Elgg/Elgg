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
	if (get_context() == "friends" ||
		get_context() == "friendsof" ||
		get_context() == "collections") {
			add_submenu_item(elgg_echo('friends:invite'),elgg_get_site_url()."mod/invitefriends/",'invite');
	}
}

register_action('invitefriends/invite', false, $CONFIG->pluginspath . 'invitefriends/actions/invite.php');
register_elgg_event_handler('pagesetup', 'system', 'invitefriends_pagesetup');
