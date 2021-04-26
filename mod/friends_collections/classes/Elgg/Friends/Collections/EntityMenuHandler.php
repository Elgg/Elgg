<?php

namespace Elgg\Friends\Collections;

use Elgg\Hook;
use ElggAccessCollection;
use ElggMenuItem;
use ElggUser;

/**
 * Register entity menu item
 */
class EntityMenuHandler {

	/**
	 * Setup entity menu
	 *
	 * @param \Elgg\Hook $hook 'register' 'menu:entity:user:user'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public function __invoke(Hook $hook) {

		$entity = $hook->getEntityParam();
		if (!$entity instanceof ElggUser) {
			return;
		}

		$collection = $entity->getVolatileData('friends:collection');
		if (!$collection instanceof ElggAccessCollection) {
			return;
		}

		if (!$collection->canEdit()) {
			return;
		}

		$return = $hook->getValue();
		
		$return[] = ElggMenuItem::factory([
			'name' => 'remove_member',
			'text' => elgg_echo('remove'),
			'href' => elgg_http_add_url_query_elements('action/friends/collections/remove_member', [
				'collection_id' => $collection->id,
				'user_guid' => $entity->guid,
			]),
			'is_action' => true,
			'confirm' => true,
		]);

		return $return;
	}

}
