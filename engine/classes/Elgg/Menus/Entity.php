<?php

namespace Elgg\Menus;

use Elgg\Menu\MenuItems;

/**
 * Register menu items to the entity menu
 *
 * @since 4.0
 * @internal
 */
class Entity {

	/**
	 * Register the edit menu item
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:entity'
	 *
	 * @return void|MenuItems
	 */
	public static function registerEdit(\Elgg\Hook $hook) {
		$entity = $hook->getEntityParam();
		if (!($entity instanceof \ElggEntity) || $entity instanceof \ElggUser) {
			// users mostly use the hover menu for their actions
			return;
		}
		
		$edit_url = elgg_generate_entity_url($entity, 'edit');
		
		if (empty($edit_url) || !$entity->canEdit()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'edit',
			'icon' => 'edit',
			'text' => elgg_echo('edit'),
			'title' => elgg_echo('edit:this'),
			'href' => $edit_url,
			'priority' => 900,
		]);
		
		return $return;
	}
	
	/**
	 * Register the delete menu item
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:entity'
	 *
	 * @return void|MenuItems
	 */
	public static function registerDelete(\Elgg\Hook $hook) {
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \ElggEntity || $entity instanceof \ElggUser || $entity instanceof \ElggPlugin || $entity instanceof \ElggUpgrade) {
			// users mostly use the hover menu for their actions
			// plugins can't be removed
			// upgrades deleting has no point, they'll be rediscovered again
			return;
		}
		
		$delete_url = elgg_generate_action_url('entity/delete', [
			'guid' => $entity->guid,
		]);
		
		if (empty($delete_url) || !$entity->canDelete()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'delete',
			'icon' => 'delete',
			'text' => elgg_echo('delete'),
			'title' => elgg_echo('delete:this'),
			'href' => $delete_url,
			'confirm' => elgg_echo('deleteconfirm'),
			'priority' => 950,
		]);
		
		return $return;
	}
	
	/**
	 * Registers menu items for the entity menu of a plugin
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:entity:object:plugin'
	 *
	 * @return void|MenuItems
	 */
	public static function registerPlugin(\Elgg\Hook $hook) {
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \ElggPlugin || !$entity->canEdit()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		if (elgg_view_exists("plugins/{$entity->getID()}/settings")) {
			$return[] = \ElggMenuItem::factory([
				'name' => 'settings',
				'icon' => 'settings-alt',
				'text' => elgg_echo('settings'),
				'href' => elgg_generate_url('admin:plugin_settings', [
					'plugin_id' => $entity->getID(),
				]),
				'section' => 'admin'
			]);
		}
		
		$priority = $entity->getPriority();
		
		// top and up link only if not at top
		if ($priority > 1) {
			$return[] = \ElggMenuItem::factory([
				'name' => 'top',
				'icon' => 'angle-double-up',
				'text' => elgg_echo('top'),
				'href' => elgg_generate_action_url('admin/plugins/set_priority', [
					'plugin_guid' => $entity->guid,
					'priority' => 'first',
				]),
				'priority' => 11,
			]);
			
			$return[] = \ElggMenuItem::factory([
				'name' => 'up',
				'icon' => 'angle-up',
				'text' => elgg_echo('up'),
				'href' => elgg_generate_action_url('admin/plugins/set_priority', [
					'plugin_guid' => $entity->guid,
					'priority' => '-1',
				]),
				'priority' => 12,
			]);
		}
		
		// down and bottom links only if not at bottom
		if ($priority < _elgg_services()->plugins->getMaxPriority()) {
			$return[] = \ElggMenuItem::factory([
				'name' => 'down',
				'icon' => 'angle-down',
				'text' => elgg_echo('down'),
				'href' => elgg_generate_action_url('admin/plugins/set_priority', [
					'plugin_guid' => $entity->guid,
					'priority' => '+1',
				]),
				'priority' => 13,
			]);
			
			$return[] = \ElggMenuItem::factory([
				'name' => 'bottom',
				'icon' => 'angle-double-down',
				'text' => elgg_echo('bottom'),
				'href' => elgg_generate_action_url('admin/plugins/set_priority', [
					'plugin_guid' => $entity->guid,
					'priority' => 'last',
				]),
				'priority' => 14,
			]);
		}
		
		// remove all user and plugin settings
		$return[] = \ElggMenuItem::factory([
			'name' => 'remove_settings',
			'icon' => 'trash-alt',
			'text' => elgg_echo('plugins:settings:remove:menu:text'),
			'href' => elgg_generate_action_url('plugins/settings/remove', [
				'plugin_id' => $entity->getID(),
			]),
			'confirm' => elgg_echo('plugins:settings:remove:menu:confirm'),
		]);
		
		return $return;
	}
	
	/**
	 * Add menu items to the entity menu of ElggUpgrade
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:entity:object:elgg_upgrade'
	 *
	 * @return void|MenuItems
	 */
	public static function registerUpgrade(\Elgg\Hook $hook) {
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \ElggUpgrade || !$entity->canEdit()) {
			return;
		}
		
		/* @var $return MenuItems */
		$result = $hook->getValue();
		
		if (!$entity->isCompleted()) {
			$result[] = \ElggMenuItem::factory([
				'name' => 'run_upgrade',
				'icon' => 'play',
				'text' => elgg_echo('admin:upgrades:menu:run_single'),
				'href' => false,
				'deps' => [
					'core/js/upgrader',
				],
				'data-guid' => $entity->guid,
				'priority' => 600,
			]);
		} elseif ($batch = $entity->getBatch()) {
			if (!$batch->shouldBeSkipped()) {
				// only show reset if it will have an effect
				$result[] = \ElggMenuItem::factory([
					'name' => 'reset',
					'icon' => 'sync',
					'text' => elgg_echo('reset'),
					'href' => elgg_generate_action_url('admin/upgrade/reset', [
						'guid' => $entity->guid,
					]),
					'priority' => 600,
				]);
			}
		}
		
		return $result;
	}
}
