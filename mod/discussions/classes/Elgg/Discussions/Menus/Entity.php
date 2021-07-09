<?php

namespace Elgg\Discussions\Menus;

use Elgg\Menu\MenuItems;

/**
 * Entity menu changed / additions for discussions
 *
 * @since 4.0
 */
class Entity {
	
	/**
	 * Register menu items to quickly toggle the open/closed status of a discussion
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:entity'
	 *
	 * @return MenuItems|null
	 */
	public static function registerStatusToggle(\Elgg\Hook $hook): ?MenuItems {
		
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \ElggDiscussion || !$entity->canEdit()) {
			return null;
		}
		
		/* @var $result MenuItems */
		$result = $hook->getValue();
		
		// add menu items
		$result[] = \ElggMenuItem::factory([
			'name' => 'status_change_open',
			'text' => elgg_echo('open'),
			'icon' => 'unlock',
			'confirm' => elgg_echo('discussion:topic:toggle_status:open:confirm'),
			'href' => elgg_generate_action_url('discussion/toggle_status', [
				'guid' => $entity->guid,
			]),
			'priority' => 200,
			'item_class' => ($entity->status === 'closed') ? '' : 'hidden',
			'data-toggle' => 'status-change-close',
		]);
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'status_change_close',
			'text' => elgg_echo('close'),
			'icon' => 'lock',
			'confirm' => elgg_echo('discussion:topic:toggle_status:closed:confirm'),
			'href' => elgg_generate_action_url('discussion/toggle_status', [
				'guid' => $entity->guid,
			]),
			'priority' => 201,
			'item_class' => ($entity->status === 'closed') ? 'hidden' : '',
			'data-toggle' => 'status-change-open',
		]);
		
		return $result;
	}
}
