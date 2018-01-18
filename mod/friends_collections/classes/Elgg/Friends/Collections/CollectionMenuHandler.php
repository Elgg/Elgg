<?php

namespace Elgg\Friends\Collections;

use Elgg\Hook;
use ElggAccessCollection;
use ElggMenuItem;

/**
 * Handle menu item registration
 */
class CollectionMenuHandler {

	/**
	 * Setup collection menu
	 *
	 * @param \Elgg\Hook $hook 'register' 'menu:friends:collection'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public function __invoke(Hook $hook) {

		$collection = $hook->getParam('collection');
		if (!$collection instanceof ElggAccessCollection) {
			return;
		}

		if (!$collection->canEdit()) {
			return;
		}

		$return = $hook->getValue();
		
		$return[] = ElggMenuItem::factory([
			'name' => 'edit',
			'text' => elgg_echo('edit'),
			'href' => "friends/collections/edit/$collection->id",
			'priority' => 500,
		]);

		$return[] = ElggMenuItem::factory([
			'name' => 'delete',
			'text' => elgg_echo('delete'),
			'href' => elgg_http_add_url_query_elements("action/friends/collections/delete", [
				'collection_id' => $collection->id,
			]),
			'is_action' => true,
			'confirm' => true,
			'priority' => 900,
		]);

		return $return;
	}

}
