<?php
/**
 * Elgg friends library.
 * Provides the UI for friends. Includes access collections since they are based
 * on friends relationships.
 *
 * @package Elgg.Core
 * @subpackage Friends
 */

elgg_register_event_handler('init', 'system', '_elgg_friends_init');

/**
 * Init friends library
 *
 * @access private
 */
function _elgg_friends_init() {
	elgg_register_action('friends/add');
	elgg_register_action('friends/remove');

	elgg_register_action('friends/collections/add');
	elgg_register_action('friends/collections/delete');
	elgg_register_action('friends/collections/edit');

	elgg_register_page_handler('friends', 'friends_page_handler');
	elgg_register_page_handler('friendsof', 'friends_page_handler');
	elgg_register_page_handler('collections', 'collections_page_handler');

	elgg_register_widget_type('friends', elgg_echo('friends'), elgg_echo('friends:widget:description'));

	elgg_register_event_handler('pagesetup', 'system', '_elgg_friends_page_setup');
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', '_elgg_friends_setup_user_hover_menu');
}

/**
 * Register some menu items for friends UI
 * @access private
 */
function _elgg_friends_page_setup() {
	$owner = elgg_get_page_owner_entity();
	$viewer = elgg_get_logged_in_user_entity();

	if ($owner) {
		$params = array(
			'name' => 'friends',
			'text' => elgg_echo('friends'),
			'href' => 'friends/' . $owner->username,
			'contexts' => array('friends')
		);
		elgg_register_menu_item('page', $params);

		$params = array(
			'name' => 'friends:of',
			'text' => elgg_echo('friends:of'),
			'href' => 'friendsof/' . $owner->username,
			'contexts' => array('friends')
		);
		elgg_register_menu_item('page', $params);
	}

	// topbar
	if ($viewer) {
		elgg_register_menu_item('topbar', array(
			'name' => 'friends',
			'href' => "friends/{$viewer->username}",
			'text' => elgg_view_icon('users'),
			'title' => elgg_echo('friends'),
			'priority' => 300,
		));
	}
}

/**
 * Adds friending to user hover menu
 *
 * @access private
 */
function _elgg_friends_setup_user_hover_menu($hook, $type, $return, $params) {
	$user = $params['entity'];
	/* @var ElggUser $user */

	if (elgg_is_logged_in()) {
		if (elgg_get_logged_in_user_guid() != $user->guid) {
			$isFriend = $user->isFriend();

			// Always emit both to make it super easy to toggle with ajax
			$return[] = ElggMenuItem::factory(array(
				'name' => 'remove_friend',
				'href' => elgg_add_action_tokens_to_url("action/friends/remove?friend={$user->guid}"),
				'text' => elgg_echo('friend:remove'),
				'section' => 'action',
				'item_class' => $isFriend ? '' : 'hidden',
			));

			$return[] = ElggMenuItem::factory(array(
				'name' => 'add_friend',
				'href' => elgg_add_action_tokens_to_url("action/friends/add?friend={$user->guid}"),
				'text' => elgg_echo('friend:add'),
				'section' => 'action',
				'item_class' => $isFriend ? 'hidden' : '',
			));
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
function friends_page_handler($segments, $handler) {
	elgg_set_context('friends');

	if (isset($segments[0]) && $user = get_user_by_username($segments[0])) {
		elgg_set_page_owner_guid($user->getGUID());
	}
	if (elgg_get_logged_in_user_guid() == elgg_get_page_owner_guid()) {
		collections_submenu_items();
	}

	switch ($handler) {
		case 'friends':
			require_once(dirname(dirname(dirname(__FILE__))) . "/pages/friends/index.php");
			break;
		case 'friendsof':
			require_once(dirname(dirname(dirname(__FILE__))) . "/pages/friends/of.php");
			break;
		default:
			return false;
	}
	return true;
}

/**
 * Page handler for friends collections
 *
 * @param array $page_elements Page elements
 *
 * @return bool
 * @access private
 */
function collections_page_handler($page_elements) {
	elgg_set_context('friends');
	$base = elgg_get_config('path');
	if (isset($page_elements[0])) {
		if ($page_elements[0] == "add") {
			elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
			collections_submenu_items();
			require_once "{$base}pages/friends/collections/add.php";
			return true;
		} else {
			$user = get_user_by_username($page_elements[0]);
			if ($user) {
				elgg_set_page_owner_guid($user->getGUID());
				if (elgg_get_logged_in_user_guid() == elgg_get_page_owner_guid()) {
					collections_submenu_items();
				}
				require_once "{$base}pages/friends/collections/view.php";
				return true;
			}
		}
	}
	return false;
}

/**
 * Adds collection submenu items
 *
 * @return void
 * @access private
 */
function collections_submenu_items() {

	$user = elgg_get_logged_in_user_entity();

	elgg_register_menu_item('page', array(
		'name' => 'friends:view:collections',
		'text' => elgg_echo('friends:collections'),
		'href' => "collections/$user->username",
	));
}
