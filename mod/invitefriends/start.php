<?php
/**
 * Elgg invite friends
 *
 * @package ElggInviteFriends
 */

elgg_register_event_handler('init', 'system', 'invitefriends_init');

function invitefriends_init() {
	register_page_handler('invite', 'invitefriends_page_handler');

	elgg_register_action('invitefriends/invite', elgg_get_plugins_path() . 'invitefriends/actions/invite.php');

	$params = array(
		'name' => 'invite',
		'title' => elgg_echo('friends:invite'),
		'url' => "pg/invite/",
		'contexts' => array('friends'),
	);
	elgg_register_menu_item('page', $params);
}

/**
 * Page handler function
 * 
 * @param array $page Page URL segments
 */
function invitefriends_page_handler($page) {
	gatekeeper();

	elgg_set_context('friends');
	set_page_owner(get_loggedin_userid());

	$title = elgg_echo('friends:invite');

	$body = elgg_view('invitefriends/form');

	$params = array(
		'content' => $body,
		'title' => $title,
	);
	$body = elgg_view_layout('one_sidebar', $params);

	echo elgg_view_page($title, $body);
}
