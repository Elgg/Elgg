<?php

namespace Elgg\Discussions\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class OwnerBlock {

	/**
	 * Register group item to menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:owner_block'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function registerGroupItem(\Elgg\Event $event) {
		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggGroup) {
			return;
		}
		
		if (!$entity->isToolEnabled('forum')) {
			return;
		}
		
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'discussion',
			'text' => elgg_echo('collection:object:discussion:group'),
			'href' => elgg_generate_url('collection:object:discussion:group', [
				'guid' => $entity->guid,
			]),
		]);

		return $return;
	}
}
