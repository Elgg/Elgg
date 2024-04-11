<?php

namespace Elgg\Menus;

use Elgg\Menu\MenuItems;

/**
 * Add items to the entity:trash menu
 */
class EntityTrash {
	
	/**
	 * Register the restore menu item
	 *
	 * @param \Elgg\Event $event 'register', 'menu:entity:trash'
	 *
	 * @return null|MenuItems
	 */
	public static function registerRestore(\Elgg\Event $event): ?MenuItems {
		$entity = $event->getEntityParam();
		if (!$entity->canEdit() || !$entity->hasCapability('restorable')) {
			return null;
		}
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
		$container = $entity->getContainerEntity();
		if ($container instanceof \ElggEntity && !$container->isDeleted()) {
			$return[] = \ElggMenuItem::factory([
				'name' => 'restore',
				'icon' => 'trash-restore-alt',
				'text' => elgg_echo('restore:this'),
				'href' => elgg_generate_action_url('entity/restore', [
					'guid' => $entity->guid,
				]),
				'confirm' => elgg_echo('restoreconfirm'),
				'priority' => 900,
			]);
		} else {
			$return[] = \ElggMenuItem::factory([
				'name' => 'restore_and_move',
				'icon' => 'trash-restore-alt',
				'text' => elgg_echo('restore:this:move'),
				'title' => elgg_echo('restore:this'),
				'href' => elgg_http_add_url_query_elements('ajax/form/entity/chooserestoredestination', [
					'entity_guid' => $entity->guid,
				]),
				'link_class' => 'elgg-lightbox',
				'priority' => 800,
			]);
		}
		
		return $return;
	}
}
