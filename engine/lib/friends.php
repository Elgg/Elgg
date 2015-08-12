<?php
/**
 * Elgg friends library.
 * Provides the UI for friends. Includes access collections since they are based
 * on friends relationships.
 *
 * @package Elgg.Core
 * @subpackage Friends
 */

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

	elgg_register_page_handler('friends', '_elgg_friends_page_handler');
	elgg_register_page_handler('friendsof', '_elgg_friends_page_handler');
	elgg_register_page_handler('collections', '_elgg_collections_page_handler');

	elgg_register_widget_type('friends', elgg_echo('friends'), elgg_echo('friends:widget:description'));

	elgg_register_event_handler('pagesetup', 'system', '_elgg_friends_page_setup');
	elgg_register_event_handler('pagesetup', 'system', '_elgg_setup_collections_menu');
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', '_elgg_friends_setup_user_hover_menu');
	elgg_register_event_handler('create', 'friend', '_elgg_send_friend_notification');
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
	/* @var \ElggUser $user */

	if (elgg_is_logged_in()) {
		if (elgg_get_logged_in_user_guid() != $user->guid) {
			$isFriend = $user->isFriend();

			// Always emit both to make it super easy to toggle with ajax
			$return[] = \ElggMenuItem::factory(array(
				'name' => 'remove_friend',
				'href' => elgg_add_action_tokens_to_url("action/friends/remove?friend={$user->guid}"),
				'text' => elgg_echo('friend:remove'),
				'section' => 'action',
				'item_class' => $isFriend ? '' : 'hidden',
			));

			$return[] = \ElggMenuItem::factory(array(
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
function _elgg_collections_page_handler($page_elements) {
	elgg_set_context('friends');
	$base = elgg_get_config('path');
	if (isset($page_elements[0])) {
		switch ($page_elements[0]) {
			case 'add':
				elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
				
				require_once "{$base}pages/friends/collections/add.php";
				return true;
				break;
			case 'owner':
				$user = get_user_by_username($page_elements[1]);
				if ($user) {
					elgg_set_page_owner_guid($user->getGUID());
					
					require_once "{$base}pages/friends/collections/view.php";
					return true;
				}
				break;
		}
	}
	return false;
}

/**
 * Adds collection sidebar menu items
 *
 * @return void
 * @access private
 */
function _elgg_setup_collections_menu() {
	
	if (elgg_get_logged_in_user_guid() == elgg_get_page_owner_guid()) {
		$user = elgg_get_page_owner_entity();
		
		elgg_register_menu_item('page', array(
			'name' => 'friends:view:collections',
			'text' => elgg_echo('friends:collections'),
			'href' => "collections/owner/$user->username",
			'contexts' => array('friends')
		));
	}
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
	$user_one = get_entity($object->guid_one);
	/* @var \ElggUser $user_one */

	$user_two = get_entity($object->guid_two);
	/* @var ElggUser $user_two */

	// Notification subject
	$subject = elgg_echo('friend:newfriend:subject', array(
		$user_one->name
	), $user_two->language);

	// Notification body
	$body = elgg_echo("friend:newfriend:body", array(
		$user_one->name,
		$user_one->getURL()
	), $user_two->language);

	// Notification params
	$params = [
		'action' => 'add_friend',
		'object' => $user_one,
	];
	
	return notify_user($user_two->guid, $object->guid_one, $subject, $body, $params);
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_friends_init');
};
