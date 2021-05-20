<?php

namespace Elgg\Friends\Menus;

use Elgg\Menu\MenuItems;
use Elgg\Router\Route;

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
	 * @param \Elgg\Hook $hook 'register', 'menu:filter:filter'
	 *
	 * @return MenuItems|null
	 */
	public static function registerFilterTabs(\Elgg\Hook $hook): ?MenuItems {
		
		$user = elgg_get_logged_in_user_entity();
		if (!$user instanceof \ElggUser) {
			return null;
		}
		
		/* @var $result MenuItems */
		$result = $hook->getValue();
		
		$entity_type = $hook->getParam('entity_type', '');
		$entity_subtype = $hook->getParam('entity_subtype', '');
		if (empty($entity_type) || empty($entity_subtype)) {
			$route = elgg_get_current_route();
			if ($route instanceof Route) {
				// assume route name as '<purpose>:<entity type>:<entity subtype>:<sub>'
				// eg collection:object:blog:owner or view:group:group
				// @see http://learn.elgg.org/en/stable/guides/routing.html#routes-names
				$route_parts = explode(':', $route->getName());
				$entity_type = elgg_extract(1, $route_parts, '');
				$entity_subtype = elgg_extract(2, $route_parts, '');
			}
		}
		
		$friend_link = $hook->getParam('friend_link');
		if (empty($friend_link)) {
			if (elgg_route_exists("collection:{$entity_type}:{$entity_subtype}:friends")) {
				$friend_link = elgg_generate_url("collection:{$entity_type}:{$entity_subtype}:friends", [
					'username' => $user->username,
				]);
			} elseif (elgg_route_exists("collection:{$entity_type}:friends")) {
				$friend_link = elgg_generate_url("collection:{$entity_type}:friends", [
					'username' => $user->username,
				]);
			}
		}
		
		if (empty($friend_link)) {
			return null;
		}
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'friends',
			'text' => elgg_echo('friends'),
			'href' => $friend_link,
			'priority' => 400,
		]);
		
		return $result;
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
