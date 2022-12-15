<?php

namespace Elgg\Friends\Collections;

/**
 * Register entity menu item
 */
class EntityMenuHandler {

	/**
	 * Setup entity menu
	 *
	 * @param \Elgg\Event $event 'register' 'menu:entity:user:user'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public function __invoke(\Elgg\Event $event) {
		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggUser) {
			return;
		}

		$collection = $entity->getVolatileData('friends:collection');
		if (!$collection instanceof \ElggAccessCollection) {
			return;
		}

		if (!$collection->canEdit()) {
			return;
		}

		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'remove_member',
			'text' => elgg_echo('remove'),
			'href' => elgg_generate_action_url('friends/collections/remove_member', [
				'collection_id' => $collection->id,
				'user_guid' => $entity->guid,
			]),
			'confirm' => true,
			'icon' => 'user-minus',
		]);

		return $return;
	}
}
