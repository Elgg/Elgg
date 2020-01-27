<?php

use Elgg\Friends\Notifications;
use Elgg\Menu\MenuItems;

/**
 * Friends init
 *
 * @return void
 */
function elgg_friends_plugin_init() {
	elgg_register_plugin_hook_handler('access:collections:write:subtypes', 'user', '_elgg_friends_register_access_type');
	elgg_register_plugin_hook_handler('filter_tabs', 'all', '_elgg_friends_filter_tabs', 1);
	
	elgg_register_event_handler('create', 'relationship', '_elgg_send_friend_notification');
	elgg_register_event_handler('delete', 'relationship', '\Elgg\Friends\Relationships::deleteFriendRelationship');

	elgg_register_plugin_hook_handler('entity:url', 'object', '_elgg_friends_widget_urls');
	
	elgg_register_plugin_hook_handler('register', 'menu:page', '_elgg_friends_page_menu');
	elgg_register_plugin_hook_handler('register', 'menu:topbar', '_elgg_friends_topbar_menu');
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', '_elgg_friends_setup_user_hover_menu');
	elgg_register_plugin_hook_handler('register', 'menu:title', '_elgg_friends_setup_title_menu');
	elgg_register_plugin_hook_handler('register', 'menu:filter:friends', '\Elgg\Friends\FilterMenu::addFriendRequestTabs');
	elgg_register_plugin_hook_handler('register', 'menu:relationship', '\Elgg\Friends\RelationshipMenu::addPendingFriendRequestItems');
	elgg_register_plugin_hook_handler('register', 'menu:relationship', '\Elgg\Friends\RelationshipMenu::addSentFriendRequestItems');
}

/**
 * Adds friending to profile title menu
 *
 * @param \Elgg\Hook $hook 'register', 'menu:title'
 *
 * @return void|MenuItems
 *
 * @internal
 */
function _elgg_friends_setup_title_menu(\Elgg\Hook $hook) {
	
	$user = $hook->getEntityParam();
	if (!$user instanceof ElggUser) {
		return;
	}
	
	/* @var $return \Elgg\Menu\MenuItems */
	$return = $hook->getValue();
	
	$menu_items = _elgg_friends_get_add_friend_menu_items($user, true);
	
	$return->merge($menu_items);
	
	return $return;
}

/**
 * Adds friending to user hover menu
 *
 * @param \Elgg\Hook $hook 'register', 'menu:user_hover'
 *
 * @return void|MenuItems
 *
 * @internal
 */
function _elgg_friends_setup_user_hover_menu(\Elgg\Hook $hook) {
	
	$user = $hook->getEntityParam();
	if (!$user instanceof ElggUser) {
		return;
	}
	
	/* @var $return \Elgg\Menu\MenuItems */
	$return = $hook->getValue();
	
	$menu_items = _elgg_friends_get_add_friend_menu_items($user);
	
	$return->merge($menu_items);
	
	return $return;
}

/**
 * Generate menu items to add the user as a friend
 *
 * @param \ElggUser $user        the potential friend
 * @param bool      $make_button make the menu items buttons (default: false)
 *
 * @return ElggMenuItem[]
 * @internal
 * @since 3.2
 */
function _elgg_friends_get_add_friend_menu_items(\ElggUser $user, bool $make_button = false) {
	
	$current_user = elgg_get_logged_in_user_entity();
	if (!$current_user instanceof \ElggUser || $user->guid === $current_user->guid) {
		return [];
	}
	
	$result = [];
	$isFriend = $user->isFriendOf($current_user->guid);
	
	// Always emit both to make it super easy to toggle with ajax
	$result[] = \ElggMenuItem::factory([
		'name' => 'remove_friend',
		'icon' => 'user-times',
		'text' => elgg_echo('friend:remove'),
		'href' => elgg_generate_action_url('friends/remove', [
			'friend' => $user->guid,
		]),
		'section' => 'action',
		'link_class' => $make_button ? 'elgg-button elgg-button-action' : null,
		'item_class' => $isFriend ? '' : 'hidden',
		'data-toggle' => 'add_friend',
	]);
	
	$add_toggle = 'remove_friend';
	$pending_request = false;
	if ((bool) elgg_get_plugin_setting('friend_request', 'friends')) {
		$sent_request = (bool) check_entity_relationship($user->guid, 'friendrequest', $current_user->guid);
		$pending_request = (bool) check_entity_relationship($current_user->guid, 'friendrequest', $user->guid);
		if (!$isFriend && !$sent_request) {
			// no current friend, and no pending request
			$add_toggle = 'friend_requests';
		}
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'friend_requests',
			'icon' => 'user-plus',
			'text' => elgg_echo('friends:menu:request:status:pending'),
			'href' => elgg_generate_url('collection:relationship:friendrequest:sent', [
				'username' => $current_user->username,
			]),
			'section' => 'action',
			'link_class' => $make_button ? 'elgg-button elgg-button-action-done' : null,
			'item_class' => $pending_request ? '' : 'hidden',
		]);
	}
	
	$result[] = \ElggMenuItem::factory([
		'name' => 'add_friend',
		'icon' => 'user-plus',
		'text' => elgg_echo('friend:add'),
		'href' => elgg_generate_action_url('friends/add', [
			'friend' => $user->guid,
		]),
		'section' => 'action',
		'link_class' =>  $make_button ? 'elgg-button elgg-button-action' : null,
		'item_class' => ($pending_request || $isFriend) ? 'hidden' : '',
		'data-toggle' => $add_toggle,
	]);
	
	return $result;
}

/**
 * Register menu items for the topbar menu
 *
 * @param \Elgg\Hook $hook 'register', 'menu:topbar'
 *
 * @return void|MenuItems
 *
 * @internal
 * @since 3.0
 */
function _elgg_friends_topbar_menu(\Elgg\Hook $hook) {

	$viewer = elgg_get_logged_in_user_entity();
	if (!$viewer) {
		return;
	}
	
	$badge = null;
	if ((bool) elgg_get_plugin_setting('friend_request', 'friends')) {
		$count = elgg_get_relationships([
			'type' => 'user',
			'relationship_guid' => $viewer,
			'relationship' => 'friendrequest',
			'inverse_relationship' => true,
			'count' => true,
		]);
		if ($count > 0) {
			$badge = $count;
		}
	}
	
	$return = $hook->getValue();
	$return[] = \ElggMenuItem::factory([
		'name' => 'friends',
		'icon' => 'users',
		'text' => elgg_echo('friends'),
		'href' => elgg_generate_url('collection:friends:owner', [
			'username' => $viewer->username,
		]),
		'badge' => $badge,
		'title' => elgg_echo('friends'),
		'priority' => 300,
		'section' => 'alt',
		'parent_name' => 'account',
	]);
	
	return $return;
}

/**
 * Register menu items for the friends page menu
 *
 * @param \Elgg\Hook $hook 'register', 'menu:page'
 *
 * @return void|MenuItems
 *
 * @internal
 * @since 3.0
 */
function _elgg_friends_page_menu(\Elgg\Hook $hook) {

	$owner = elgg_get_page_owner_entity();
	if (!$owner instanceof ElggUser) {
		return;
	}

	$return = $hook->getValue();
	$return[] = \ElggMenuItem::factory([
		'name' => 'friends',
		'text' => elgg_echo('friends'),
		'href' => elgg_generate_url('collection:friends:owner', [
			'username' => $owner->username,
		]),
		'contexts' => ['friends'],
	]);

	if (!(bool) elgg_get_plugin_setting('friend_request', 'friends')) {
		$return[] = \ElggMenuItem::factory([
			'name' => 'friends:of',
			'text' => elgg_echo('friends:of'),
			'href' => elgg_generate_url('collection:friends_of:owner', [
				'username' => $owner->username,
			]),
			'contexts' => ['friends'],
		]);
	}

	return $return;
}

/**
 * Register friends to the write access array
 *
 * @param \Elgg\Hook $hook 'access:collections:write:subtypes', 'user'
 *
 * @return array
 *
 * @internal
 * @since 3.2
 */
function _elgg_friends_register_access_type(\Elgg\Hook $hook) {
	$return = $hook->getValue();
	$return[] = 'friends';
	return $return;
}

/**
 * Notify user that someone has friended them
 *
 * @param \Elgg\Event $event 'create', 'relationship'
 *
 * @return void
 * @internal
 */
function _elgg_send_friend_notification(\Elgg\Event $event) {
	
	if ((bool) elgg_get_plugin_setting('friend_request', 'friends')) {
		// no generic notification while friend request is active
		// notifications are sent by actions
		return;
	}
	
	$object = $event->getObject();
	if (!$object instanceof ElggRelationship || $object->relationship !== 'friend') {
		return;
	}

	if ($object->guid_two === elgg_get_logged_in_user_guid()) {
		// don't send notification to yourself
		return;
	}
	
	$user_one = get_user($object->guid_one);
	$user_two = get_user($object->guid_two);
	if (!$user_one instanceof ElggUser || !$user_two instanceof ElggUser) {
		return;
	}

	Notifications::sendAddFriendNotification($user_two, $user_one);
}

/**
 * Add "Friends" tab to common filter
 *
 * @param \Elgg\Hook $hook "filter_tabs", "all"
 *
 * @return void|ElggMenuItem[]
 * @internal
 */
function _elgg_friends_filter_tabs(\Elgg\Hook $hook) {

	$user = $hook->getUserParam();
	if (!$user instanceof ElggUser) {
		return;
	}

	$vars = $hook->getParam('vars');
	$selected = $hook->getParam('selected');
	$type = $hook->getType();

	$items = $hook->getValue();
	$items[] = ElggMenuItem::factory([
		'name' => 'friend',
		'text' => elgg_echo('friends'),
		'href' => (isset($vars['friend_link'])) ? $vars['friend_link'] : "$type/friends/{$user->username}",
		'selected' => ($selected == 'friends'),
		'priority' => 400,
	]);
	return $items;
}


/**
 * Returns widget URLS used in widget titles
 *
 * @param \Elgg\Hook $hook 'entity:url', 'object'
 *
 * @return void|string
 * @internal
 */
function _elgg_friends_widget_urls(\Elgg\Hook $hook) {
	$widget = $hook->getEntityParam();
	if (!$widget instanceof \ElggWidget) {
		return;
	}
	
	if ($widget->handler !== 'friends') {
		return;
	}
	
	$owner = $widget->getOwnerEntity();
	if (!$owner instanceof \ElggUser) {
		return;
	}
	
	$url = elgg_generate_url('collection:friends:owner', [
		'username' => $owner->username,
	]);
	if (empty($url)) {
		return;
	}
	return $url;
}

return function() {
	elgg_register_event_handler('init', 'system', 'elgg_friends_plugin_init');
};
