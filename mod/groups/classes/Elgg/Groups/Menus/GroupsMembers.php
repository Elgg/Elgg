<?php

namespace Elgg\Groups\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class GroupsMembers {

	/**
	 * Setup group members tabs
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:groups_members'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
	
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \ElggGroup) {
			return;
		}
	
		$menu = $hook->getValue();
		$menu[] = \ElggMenuItem::factory([
			'name' => 'alpha',
			'text' => elgg_echo('sort:alpha'),
			'href' => elgg_generate_url('collection:user:user:group_members', [
				'guid' => $entity->guid,
			]),
			'priority' => 100
		]);
	
		$menu[] = \ElggMenuItem::factory([
			'name' => 'newest',
			'text' => elgg_echo('sort:newest'),
			'href' => elgg_generate_url('collection:user:user:group_members', [
				'guid' => $entity->guid,
				'sort' => 'newest',
			]),
			'priority' => 200
		]);
		
		if ($entity->canEdit()) {
			$menu[] = \ElggMenuItem::factory([
				'name' => 'membership_requests',
				'text' => elgg_echo('groups:membershiprequests'),
				'href' => elgg_generate_entity_url($entity, 'requests'),
				'priority' => 300
			]);
			
			$menu[] = \ElggMenuItem::factory([
				'name' => 'membership_invites',
				'text' => elgg_echo('groups:invitedmembers'),
				'href' => elgg_generate_url('collection:user:user:group_invites', [
					'guid' => $entity->guid,
				]),
				'priority' => 400,
			]);
		}
	
		return $menu;
	}
}
