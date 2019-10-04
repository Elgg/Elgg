<?php

namespace Elgg\Friends;

use Elgg\Menu\MenuItems;

/**
 * Add tabs to the filter menu in the friends context
 *
 * @since 3.2
 */
class FilterMenu {

	/**
	 * Add the friend request tabs
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:filter:friends'
	 *
	 * @return void|MenuItems
	 * @internal
	 * @since 3.2
	 */
	public static function addFriendRequestTabs(\Elgg\Hook $hook) {
		
		if (!(bool) elgg_get_plugin_setting('friend_request', 'friends')) {
			return;
		}
		
		$page_owner = elgg_get_page_owner_entity();
		if (!$page_owner instanceof \ElggUser || !$page_owner->canEdit()) {
			return;
		}
		
		/* @var $result MenuItems */
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
