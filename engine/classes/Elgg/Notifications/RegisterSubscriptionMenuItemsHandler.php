<?php

namespace Elgg\Notifications;

use Elgg\Menu\MenuItems;

/**
 * Register menu items to any menu to handle entity subscriptions
 *
 * This should be registered to or call from a event handler 'register', 'menu:<menu name>'
 *
 * @since 4.0
 */
class RegisterSubscriptionMenuItemsHandler {
	
	/**
	 * Add menu items to a menu
	 *
	 * The event requires
	 * - params['entity'] the entity for which to register the menu items
	 * - return needs to be a MenuItems collection
	 *
	 * @param \Elgg\Event $event 'register', 'menu:<menu name>'
	 *
	 * @return void|MenuItems
	 */
	public function __invoke(\Elgg\Event $event) {
		
		$result = $event->getValue();
		$entity = $event->getEntityParam();
		if (!$result instanceof MenuItems || !$entity instanceof \ElggEntity) {
			return;
		}
		
		if (!elgg_is_logged_in()) {
			return;
		}
		
		$link_classes = [];
		if ($event->getParam('name') === 'title') {
			$link_classes = [
				'elgg-button',
				'elgg-button-action',
			];
		}
		
		$can_subscribe = !$entity->hasSubscriptions() || $entity->hasMutedNotifications();
		
		// subscribe
		$subscribe_options = [
			'name' => 'entity_subscribe',
			'icon' => 'bell',
			'text' => elgg_echo('entity:subscribe'),
			'href' => false,
			'item_class' => $can_subscribe ? '' : 'hidden',
			'link_class' => $link_classes,
		];
		
		// check if it makes sense to enable the subscribe button
		$has_preferences = !empty(array_keys(array_filter(elgg_get_logged_in_user_entity()->getNotificationSettings())));
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
			'link_class' => $link_classes,
			'data-toggle' => 'entity_subscribe',
		]);
		
		return $result;
	}
}
