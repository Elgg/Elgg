<?php

namespace Elgg\Pages\Menus;

use Elgg\Menu\MenuItems;

/**
 * Add menu items to the title menu
 *
 * @since 7.1
 */
class Title {
	
	/**
	 * Register the create subpage menu item when viewing a page
	 *
	 * @param \Elgg\Event $event 'register', 'menu:title:object:page'
	 *
	 * @return MenuItems|null
	 */
	public static function registerAddSubpage(\Elgg\Event $event): ?MenuItems {
		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggPage || !$entity->canEdit()) {
			return null;
		}
		
		$container = $entity->getContainerEntity();
		if (!$container?->canWriteToContainer(0, 'object', 'page')) {
			return null;
		}
		
		/** @var MenuItems $result */
		$result = $event->getValue();
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'subpage',
			'icon' => 'plus',
			'href' => elgg_generate_url('add:object:page', [
				'guid' => $entity->guid,
			]),
			'text' => elgg_echo('pages:newchild'),
			'link_class' => ['elgg-button', 'elgg-button-action'],
		]);
		
		return $result;
	}
}
