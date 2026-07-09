<?php

namespace Elgg\File\Menus;

use Elgg\Menu\MenuItems;

/**
 * Add title menu items
 */
class Title {
	
	/**
	 * Add a download title menu item
	 *
	 * @param \Elgg\Event $event 'register', 'menu:title:object:file'
	 *
	 * @return MenuItems|null
	 */
	public static function registerDownload(\Elgg\Event $event): ?MenuItems {
		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggFile) {
			return null;
		}
		
		/** @var MenuItems $result */
		$result = $event->getValue();
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'download',
			'text' => elgg_echo('download'),
			'href' => $entity->getDownloadURL(),
			'icon' => 'download',
			'link_class' => ['elgg-button', 'elgg-button-action'],
		]);
		
		return $result;
	}
}
