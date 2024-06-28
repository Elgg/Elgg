<?php

namespace Elgg\Groups\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class Filter {
	
	/**
	 * Setup filter tabs on /groups/all page
	 *
	 * @param \Elgg\Event $event 'register', 'menu:filter:groups/all'
	 *
	 * @return \Elgg\Menu\MenuItems
	 */
	public static function registerGroupsAll(\Elgg\Event $event) {
	
		$return = $event->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'all',
			'text' => elgg_echo('all'),
			'href' => elgg_generate_url('collection:group:group:all'),
			'priority' => 200,
		]);
	
		$return[] = \ElggMenuItem::factory([
			'name' => 'featured',
			'text' => elgg_echo('groups:featured'),
			'href' => elgg_generate_url('collection:group:group:all', [
				'filter' => 'featured',
			]),
			'priority' => 400,
		]);
		
		return $return;
	}
	
	/**
	 * Setup filter tabs on notification settings page
	 *
	 * @param \Elgg\Event $event 'register', 'menu:filter:settings/notifications'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function registerNotificationSettings(\Elgg\Event $event) {
	
		$page_owner = elgg_get_page_owner_entity();
		if (!$page_owner instanceof \ElggUser || !$page_owner->canEdit()) {
			return;
		}
		
		/* @var $return \Elgg\Menu\MenuItems */
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'groups',
			'text' => elgg_echo('collection:group:group'),
			'href' => elgg_generate_url('settings:notification:groups', [
				'username' => $page_owner->username,
			]),
			'priority' => 300,
		]);
		
		return $return;
	}
	
	/**
	 * Setup group members tabs
	 *
	 * @param \Elgg\Event $event 'register', 'menu:filter:groups/members'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function registerGroupsMembers(\Elgg\Event $event) {
		
		$entity = $event->getParam('filter_entity');
		if (!$entity instanceof \ElggGroup) {
			return;
		}
		
		$menu = $event->getValue();
		$menu[] = \ElggMenuItem::factory([
			'name' => 'members',
			'text' => elgg_echo('groups:members'),
			'href' => elgg_generate_url('collection:user:user:group_members', [
				'guid' => $entity->guid,
			]),
			'priority' => 100,
		]);
		
		if ($entity->canEdit()) {
			$menu[] = \ElggMenuItem::factory([
				'name' => 'membership_requests',
				'text' => elgg_echo('groups:membershiprequests'),
				'href' => elgg_generate_entity_url($entity, 'requests'),
				'priority' => 300,
				'badge' => elgg_count_relationships([
					'relationship' => 'membership_request',
					'relationship_guid' => $entity->guid,
					'inverse_relationship' => true,
				]) ?: null,
			]);
			
			$menu[] = \ElggMenuItem::factory([
				'name' => 'membership_invites',
				'text' => elgg_echo('groups:invitedmembers'),
				'href' => elgg_generate_url('collection:user:user:group_invites', [
					'guid' => $entity->guid,
				]),
				'priority' => 400,
				'badge' => elgg_count_relationships([
					'relationship' => 'invited',
					'relationship_guid' => $entity->guid,
				]) ?: null,
			]);
		}
		
		return $menu;
	}
}
