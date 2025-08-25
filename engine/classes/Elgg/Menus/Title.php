<?php

namespace Elgg\Menus;

use Elgg\Menu\MenuItems;
use Elgg\Menu\UnpreparedMenu;

/**
 * Register menu items to the title menu
 *
 * @since 4.0
 * @internal
 */
class Title {
	
	/**
	 * Add a link to the avatar edit page
	 *
	 * @param \Elgg\Event $event 'register', 'menu:title'
	 *
	 * @return void|MenuItems
	 */
	public static function registerAvatarEdit(\Elgg\Event $event) {
		$user = $event->getEntityParam();
		if (!$user instanceof \ElggUser || !$user->canEdit()) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'avatar:edit',
			'icon' => 'image',
			'text' => elgg_echo('avatar:edit'),
			'href' => elgg_generate_entity_url($user, 'edit', 'avatar'),
			'link_class' => ['elgg-button', 'elgg-button-action'],
		]);
		
		return $return;
	}
	
	/**
	 * Move entity menu items to the title menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:title'
	 *
	 * @return void|MenuItems
	 */
	public static function registerEntityToTitle(\Elgg\Event $event) {
		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggEntity) {
			return;
		}
		
		$return = $event->getValue();
		if (!$return instanceof MenuItems) {
			return;
		}
		
		$entity_menu = _elgg_services()->menus->getUnpreparedMenu('entity', [
			'entity' => $entity,
		]);
		
		/* @var $menu_item \ElggmenuItem */
		foreach ($entity_menu->getItems() as $menu_item) {
			if ($return->has($menu_item->getName())) {
				if ($menu_item->getName() === 'edit') {
					// move edit always to z-last location
					$return->get('edit')->setSection('z-last');
				}
				
				continue;
			}
			
			switch ($menu_item->getName()) {
				case 'edit':
					$menu_item->addLinkClass('elgg-button');
					$menu_item->addLinkClass('elgg-button-action');
					
					$menu_item->setSection('z-last');
					break;
				default:
					if ($menu_item->getSection() === 'default') {
						$menu_item->setParentName('title-menu-toggle');
						$menu_item->setSection('z-last');
					} else {
						$menu_item->addLinkClass('elgg-button');
						$menu_item->addLinkClass('elgg-button-action');
					}
					break;
			}
			
			$return->add($menu_item);
		}
		
		$return->add(\ElggMenuItem::factory([
			'name' => 'title-menu-toggle',
			'icon' => 'ellipsis-v',
			'href' => false,
			'text' => '',
			'title' => elgg_echo('more'),
			'child_menu' => [
				'display' => 'dropdown',
				'data-position' => json_encode([
					'at' => 'right bottom',
					'my' => 'right top',
					'collision' => 'fit fit',
				]),
				'class' => "elgg-{$event->getParam('name')}-dropdown-menu",
			],
			'show_with_empty_children' => false,
			'link_class' => [
				'elgg-button',
				'elgg-button-action',
			],
			'priority' => 999,
			'section' => 'z-last',
		]));
		
		return $return;
	}
	
	/**
	 * Register (un)subscribe menu items to the title menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:title'
	 *
	 * @return null|MenuItems
	 */
	public static function registerSubscribable(\Elgg\Event $event): ?MenuItems {
		$user = elgg_get_logged_in_user_entity();
		if (!$user instanceof \ElggUser) {
			return null;
		}
		
		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggEntity || !$entity->hasCapability('subscribable')) {
			return null;
		}
		
		$result = $event->getValue();
		if (!$result instanceof MenuItems) {
			return null;
		}
		
		$can_subscribe = !$entity->hasSubscriptions() || $entity->hasMutedNotifications();
		
		// subscribe
		$subscribe_options = [
			'name' => 'entity_subscribe',
			'icon' => 'bell',
			'text' => elgg_echo('entity:subscribe'),
			'href' => false,
			'item_class' => $can_subscribe ? '' : 'hidden',
			'link_class' => [
				'elgg-button',
				'elgg-button-action',
			],
		];
		
		// check if it makes sense to enable the subscribe button
		$has_preferences = !empty($user->getNotificationSettings('default', true));
		if ($has_preferences) {
			$subscribe_options['href'] = elgg_generate_action_url('entity/subscribe', [
				'guid' => $entity->guid,
			]);
			$subscribe_options['data-toggle'] = 'entity_mute';
		} else {
			$subscribe_options['link_class'][] = 'elgg-state-disabled';
			$subscribe_options['title'] = elgg_echo('entity:subscribe:disabled');
		}
		
		$result[] = \ElggMenuItem::factory($subscribe_options);
		
		// mute
		$result[] = \ElggMenuItem::factory([
			'name' => 'entity_mute',
			'icon' => 'bell-slash',
			'text' => elgg_echo('entity:mute'),
			'href' => elgg_generate_action_url('entity/mute', [
				'guid' => $entity->guid,
			]),
			'item_class' => $can_subscribe ? 'hidden' : '',
			'link_class' => [
				'elgg-button',
				'elgg-button-action',
			],
			'data-toggle' => 'entity_subscribe',
		]);
		
		return $result;
	}
}
