<?php

namespace Elgg\Groups\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class Entity {

	/**
	 * Add join/leave menu items
	 *
	 * @param \Elgg\Event $event 'register', 'menu:entity:group:group'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggGroup) {
			return;
		}
		
		$user = elgg_get_logged_in_user_entity();
		if (empty($user)) {
			return;
		}
		
		$return = $event->getValue();
		$group_join = groups_get_group_join_menu_item($entity, $user);
		if (!empty($group_join)) {
			$return[] = $group_join;
		}
		
		$group_leave = groups_get_group_leave_menu_item($entity, $user);
		if (!empty($group_leave)) {
			$return[] = $group_leave;
		}
		
		return $return;
	}

	/**
	 * Add (un)feature toggle
	 *
	 * @param \Elgg\Event $event 'register', 'menu:entity:group:group'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function registerFeature(\Elgg\Event $event) {
		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggGroup) {
			return;
		}

        if($entity->soft_deleted === 'yes'){
            return;
        }

		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		$return = $event->getValue();
		
		$isFeatured = $entity->featured_group === 'yes';
	
		$return[] = \ElggMenuItem::factory([
			'name' => 'feature',
			'icon' => 'arrow-up',
			'text' => elgg_echo('feature'),
			'href' => elgg_generate_action_url('groups/featured', [
				'group_guid' => $entity->guid,
				'action_type' => 'feature',
			]),
			'item_class' => $isFeatured ? 'hidden' : '',
			'data-toggle' => 'unfeature',
		]);
	
		$return[] = \ElggMenuItem::factory([
			'name' => 'unfeature',
			'icon' => 'arrow-down',
			'text' => elgg_echo('unfeature'),
			'href' => elgg_generate_action_url('groups/featured', [
				'group_guid' => $entity->guid,
				'action_type' => 'unfeature',
			]),
			'item_class' => $isFeatured ? '' : 'hidden',
			'data-toggle' => 'feature',
		]);

		return $return;
	}
}
