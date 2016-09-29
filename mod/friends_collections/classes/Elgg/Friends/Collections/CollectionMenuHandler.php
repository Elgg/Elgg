<?php

namespace Elgg\Friends\Collections;

use Elgg\Hook;
use Elgg\HooksRegistrationService\Hook as Hook2;
use ElggAccessCollection;
use ElggMenuItem;

class CollectionMenuHandler {

	/**
	 * Setup collection menu
	 *
	 * @elgg_plugin_hook register menu:friends:collection
	 *
	 * @param Hook2 $hook Hook
	 * @return ElggMenuItem[]
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
					'href' => "collections/edit/$collection->id",
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
