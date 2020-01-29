<?php

namespace Elgg\Friends\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class Filter {

	/**
	 * Add 'Friends' tab to common filter
	 *
	 * @param \Elgg\Hook $hook 'filter_tabs', 'all'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function registerFilterTabs(\Elgg\Hook $hook) {
	
		$user = $hook->getUserParam();
		if (!$user instanceof \ElggUser) {
			return;
		}
	
		$vars = $hook->getParam('vars');
		$selected = $hook->getParam('selected');
		$type = $hook->getType();
	
		$items = $hook->getValue();
		$items[] = \ElggMenuItem::factory([
			'name' => 'friend',
			'text' => elgg_echo('friends'),
			'href' => (isset($vars['friend_link'])) ? $vars['friend_link'] : "$type/friends/{$user->username}",
			'selected' => ($selected == 'friends'),
			'priority' => 400,
		]);
		return $items;
	}
	
	/**
	 * Add the friend request tabs
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:filter:friends'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function addFriendRequestTabs(\Elgg\Hook $hook) {
		
		if (!(bool) elgg_get_plugin_setting('friend_request', 'friends')) {
			return;
		}
		
		$page_owner = elgg_get_page_owner_entity();
		if (!$page_owner instanceof \ElggUser || !$page_owner->canEdit()) {
			return;
		}
		
		/* @var $result \Elgg\Menu\MenuItems */
		$result = $hook->getValue();
		
		// add friends
		$result[] = \ElggMenuItem::factory([
			'name' => 'friends',
			'text' => elgg_echo('friends'),
			'href' => elgg_generate_url('collection:friends:owner', [
				'username' => $page_owner->username,
			]),
		]);
		
		$options = [
			'type' => 'user',
			'relationship' => 'friendrequest',
			'relationship_guid' => $page_owner->guid,
			'inverse_relationship' => true,
			'count' => true,
		];
		
		// add received friend requests
		$count = elgg_get_relationships($options);
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'pending',
			'text' => elgg_echo('friends:request:pending'),
			'href' => elgg_generate_url('collection:relationship:friendrequest:pending', [
				'username' => $page_owner->username
			]),
			'badge' => !empty($count) ? $count : null,
		]);
		
		// add sent friend requests
		$options['inverse_relationship'] = false;
		
		$count = elgg_get_relationships($options);
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'sent',
			'text' => elgg_echo('friends:request:sent'),
			'href' => elgg_generate_url('collection:relationship:friendrequest:sent', [
				'username' => $page_owner->username
			]),
			'badge' => !empty($count) ? $count : null,
		]);
		
		return $result;
	}
}
