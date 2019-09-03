<?php

/**
 * Friends init
 *
 * @return void
 */
function elgg_friends_plugin_init() {
	elgg_register_plugin_hook_handler('access:collections:write:subtypes', 'user', '_elgg_friends_register_access_type');
	elgg_register_plugin_hook_handler('filter_tabs', 'all', '_elgg_friends_filter_tabs', 1);

	elgg_register_event_handler('create', 'relationship', '_elgg_send_friend_notification');

	elgg_register_plugin_hook_handler('entity:url', 'object', '_elgg_friends_widget_urls');
	
	elgg_register_plugin_hook_handler('register', 'menu:page', '_elgg_friends_page_menu');
	elgg_register_plugin_hook_handler('register', 'menu:topbar', '_elgg_friends_topbar_menu');
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', '_elgg_friends_setup_user_hover_menu');
	elgg_register_plugin_hook_handler('register', 'menu:title', '_elgg_friends_setup_title_menu');
}

/**
 * Adds friending to profile title menu
 *
 * @param \Elgg\Hook $hook 'register', 'menu:title'
 *
 * @return void|ElggMenuItem[]
 *
 * @internal
 */
function _elgg_friends_setup_title_menu(\Elgg\Hook $hook) {
	
	$user = $hook->getEntityParam();
	if (!$user instanceof ElggUser || !elgg_is_logged_in()) {
		return;
	}

	if (elgg_get_logged_in_user_guid() === $user->guid) {
		return;
	}
	
	$isFriend = $user->isFriend();

	$return = $hook->getValue();
	
	// Always emit both to make it super easy to toggle with ajax
	$return[] = \ElggMenuItem::factory([
		'name' => 'remove_friend',
		'href' => elgg_generate_action_url('friends/remove', [
			'friend' => $user->guid,
		]),
		'text' => elgg_echo('friend:remove'),
		'icon' => 'user-times',
		'section' => 'action',
		'link_class' => 'elgg-button-action elgg-button',
		'item_class' => $isFriend ? '' : 'hidden',
		'data-toggle' => 'add_friend',
	]);

	$return[] = \ElggMenuItem::factory([
		'name' => 'add_friend',
		'href' => elgg_generate_action_url('friends/add', [
			'friend' => $user->guid,
		]),
		'text' => elgg_echo('friend:add'),
		'icon' => 'user-plus',
		'section' => 'action',
		'link_class' => 'elgg-button-action elgg-button',
		'item_class' => $isFriend ? 'hidden' : '',
		'data-toggle' => 'remove_friend',
	]);

	return $return;
}

/**
 * Adds friending to user hover menu
 *
 * @param \Elgg\Hook $hook 'register', 'menu:user_hover'
 *
 * @return void|ElggMenuItem[]
 *
 * @internal
 */
function _elgg_friends_setup_user_hover_menu(\Elgg\Hook $hook) {
	
	$user = $hook->getEntityParam();
	if (!$user instanceof ElggUser || !elgg_is_logged_in()) {
		return;
	}

	if (elgg_get_logged_in_user_guid() === $user->guid) {
		return;
	}
	
	$isFriend = $user->isFriend();
	$return = $hook->getValue();
	
	// Always emit both to make it super easy to toggle with ajax
	$return[] = \ElggMenuItem::factory([
		'name' => 'remove_friend',
		'href' => elgg_generate_action_url('friends/remove', [
			'friend' => $user->guid,
		]),
		'text' => elgg_echo('friend:remove'),
		'icon' => 'user-times',
		'section' => 'action',
		'item_class' => $isFriend ? '' : 'hidden',
		'data-toggle' => 'add_friend',
	]);

	$return[] = \ElggMenuItem::factory([
		'name' => 'add_friend',
		'href' => elgg_generate_action_url('friends/add', [
			'friend' => $user->guid,
		]),
		'text' => elgg_echo('friend:add'),
		'icon' => 'user-plus',
		'section' => 'action',
		'item_class' => $isFriend ? 'hidden' : '',
		'data-toggle' => 'remove_friend',
	]);

	return $return;
}

/**
 * Register menu items for the topbar menu
 *
 * @param \Elgg\Hook $hook 'register', 'menu:topbar'
 *
 * @return void|ElggMenuItem[]
 *
 * @internal
 * @since 3.0
 */
function _elgg_friends_topbar_menu(\Elgg\Hook $hook) {

	$viewer = elgg_get_logged_in_user_entity();
	if (!$viewer) {
		return;
	}
		
	$return = $hook->getValue();
	$return[] = \ElggMenuItem::factory([
		'name' => 'friends',
		'href' => elgg_generate_url('collection:friends:owner', [
			'username' => $viewer->username,
		]),
		'text' => elgg_echo('friends'),
		'icon' => 'users',
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
 * @return void|ElggMenuItem[]
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

	$return[] = \ElggMenuItem::factory([
		'name' => 'friends:of',
		'text' => elgg_echo('friends:of'),
		'href' => elgg_generate_url('collection:friends_of:owner', [
			'username' => $owner->username,
		]),
		'contexts' => ['friends'],
	]);

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
	$object = $event->getObject();
	if (!$object instanceof ElggRelationship) {
		return;
	}
	
	if ($object->relationship !== 'friend') {
		return;
	}

	$user_one = get_entity($object->guid_one);
	$user_two = get_entity($object->guid_two);
	if (!$user_one instanceof ElggUser || !$user_two instanceof ElggUser) {
		return;
	}

	// Notification subject
	$subject = elgg_echo('friend:newfriend:subject', [
		$user_one->getDisplayName(),
	], $user_two->language);

	// Notification body
	$body = elgg_echo("friend:newfriend:body", [
		$user_one->getDisplayName(),
		$user_one->getURL()
	], $user_two->language);

	// Notification params
	$params = [
		'action' => 'add_friend',
		'object' => $user_one,
		'friend' => $user_two,
		'url' => $user_two->getURL(),
	];

	notify_user($user_two->guid, $object->guid_one, $subject, $body, $params);
}

/**
 * Add "Friends" tab to common filter
 *
 * @param \Elgg\Hook $hook "filter_tabs", "all"
 *
 * @return array
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
