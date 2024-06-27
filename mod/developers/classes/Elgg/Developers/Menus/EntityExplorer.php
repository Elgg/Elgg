<?php

namespace Elgg\Developers\Menus;

use Elgg\Menu\MenuItems;

/**
 * Add menu items to the entity_explorer menu
 */
class EntityExplorer {
	
	/**
	 * Add menu items
	 *
	 * @param \Elgg\Event $event 'register', 'menu:entity_explorer'
	 *
	 * @return MenuItems|null
	 */
	public static function register(\Elgg\Event $event): ?MenuItems {
		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggEntity) {
			return null;
		}
		
		/* @var $result MenuItems */
		$result = $event->getValue();
		
		// link to entity
		if (!$entity->isDeleted()) {
			$url = $entity->getURL();
			if (!empty($url) && $url !== elgg_get_site_url()) {
				$result[] = \ElggMenuItem::factory([
					'name' => 'view',
					'icon' => 'eye',
					'text' => elgg_echo('developers:entity_explorer:view_entity'),
					'href' => $url,
					'link_class' => ['elgg-button', 'elgg-button-action'],
					'priority' => 50,
				]);
			}
		}
		
		// delete entity
		$result[] = \ElggMenuItem::factory([
			'name' => 'delete',
			'icon' => 'trash-alt',
			'text' => elgg_echo('developers:entity_explorer:delete_entity'),
			'href' => elgg_generate_action_url('developers/entity_explorer_delete', [
				'guid' => $entity->guid,
				'type' => 'entity',
				'key' => $entity->guid,
			]),
			'confirm' => elgg_echo('deleteconfirm'),
			'link_class' => ['elgg-button', 'elgg-button-delete'],
			'priority' => 9999,
		]);
		
		// fix restore action classes
		$result->get('restore')?->setLinkClass(['elgg-button', 'elgg-button-cancel']);
		$result->get('restore_and_move')?->setLinkClass(['elgg-button', 'elgg-button-cancel']);
		
		return $result;
	}
}
