<?php
/**
 * Elgg invite friends
 *
 * @package ElggInviteFriends
 */

elgg_register_event_handler('init', 'system', 'invitefriends_init');

function invitefriends_init() {
	elgg_register_page_handler('invite', 'invitefriends_page_handler');

	elgg_register_action('invitefriends/invite', elgg_get_plugins_path() . 'invitefriends/actions/invite.php');

	elgg_register_plugin_hook_handler('register', 'user', 'invitefriends_add_friends');

	if (elgg_is_logged_in() && elgg_get_config('allow_registration')) {
		$params = array(
			'name' => 'invite',
			'text' => elgg_echo('friends:invite'),
			'href' => "invite",
			'contexts' => array('friends'),
		);
		elgg_register_menu_item('page', $params);
	}
}

/**
 * Page handler function
 *
 * @param array $page Page URL segments
 * @return bool
 */
function invitefriends_page_handler($page) {
	elgg_gatekeeper();

	if (!elgg_get_config('allow_registration')) {
		return false;
	}

	elgg_set_context('friends');
	elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());

	$title = elgg_echo('friends:invite');

	$body = elgg_view('invitefriends/form');

	$params = array(
		'content' => $body,
		'title' => $title,
	);
	$body = elgg_view_layout('one_sidebar', $params);

	echo elgg_view_page($title, $body);
	return true;
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
	$user = $params['user'];
	$friend_guid = $params['friend_guid'];
	$invite_code = $params['invitecode'];

	// If $friend_guid has been set, make mutual friends
	if ($friend_guid) {
		if ($friend_user = get_user($friend_guid)) {
			if ($invite_code == generate_invite_code($friend_user->username)) {
				$user->addFriend($friend_guid);
				$friend_user->addFriend($user->guid);

				// @todo Should this be in addFriend?
				elgg_create_river_item(array(
					'view' => 'river/relationship/friend/create',
					'action_type' => 'friend',
					'subject_guid' => $user->getGUID(),
					'object_guid' => $friend_guid,
				));
				elgg_create_river_item(array(
					'view' => 'river/relationship/friend/create',
					'action_type' => 'friend',
					'subject_guid' => $friend_guid,
					'object_guid' => $user->getGUID(),
				));
			}
		}
	}
}
