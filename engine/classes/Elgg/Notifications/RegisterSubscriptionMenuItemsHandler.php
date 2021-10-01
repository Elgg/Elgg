<?php

namespace Elgg\Notifications;

use Elgg\Menu\MenuItems;

/**
 * Register menu items to any menu to handle entity subscriptions
 *
 * This should be registered to or call from a hook handler 'register', 'menu:<menu name>'
 *
 * @since 4.0
 */
class RegisterSubscriptionMenuItemsHandler {
	
	/**
	 * Add menu items to a menu
	 *
	 * The hook requires
	 * - params['entity'] the entity for which to register the menu items
	 * - return needs to be a MenuItems collection
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:<menu name>'
	 *
	 * @return void|MenuItems
	 */
	public function __invoke(\Elgg\Hook $hook) {
		
		$result = $hook->getValue();
		$entity = $hook->getEntityParam();
		if (!$result instanceof MenuItems || !$entity instanceof \ElggEntity) {
			return;
		}
		
		if (!elgg_is_logged_in()) {
			return;
		}
		
		$link_classes = [];
		if ($hook->getParam('name') === 'title') {
			$link_classes = [
				'elgg-button',
				'elgg-button-action',
			];
		}
		
		$has_subscriptions = $entity->hasSubscriptions();
		
		// subscribe
		$result[] = \ElggMenuItem::factory([
			'name' => 'entity_subscribe',
			'icon' => 'bell',
			'text' => elgg_echo('entity:subscribe'),
			'href' => elgg_generate_action_url('entity/subscribe', [
				'guid' => $entity->guid,
			]),
			'item_class' => $has_subscriptions ? 'hidden' : '',
			'link_class' => $link_classes,
			'data-toggle' => 'entity_mute',
		]);
		
		// mute
		$result[] = \ElggMenuItem::factory([
			'name' => 'entity_mute',
			'icon' => 'bell-slash',
			'text' => elgg_echo('entity:mute'),
			'href' => elgg_generate_action_url('entity/mute', [
				'guid' => $entity->guid,
			]),
			'item_class' => $has_subscriptions ? '' : 'hidden',
			'link_class' => $link_classes,
			'data-toggle' => 'entity_subscribe',
		]);
		
		return $result;
	}
}
