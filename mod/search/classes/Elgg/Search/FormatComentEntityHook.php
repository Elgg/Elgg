<?php

namespace Elgg\Search;

use Elgg\Hook;
use ElggEntity;

/**
 * Populates comment's volatile data for search listing rendering
 *
 * @elgg_plugin_hook search:format entity
 */
class FormatComentEntityHook {

	/**
	 * Format comment entity in search results
	 *
	 * @elgg_plugin_hook search:format entity
	 *
	 * @param Hook $hook Hook
	 *
	 * @return ElggEntity
	 */
	public function __invoke(Hook $hook) {

		$entity = $hook->getValue();

		if (!$entity instanceof \ElggComment) {
			return;
		}

		$icon = '';
		$owner = $entity->getOwnerEntity();
		if ($owner) {
			$size = $hook->getParam('size', 'small');
			$icon = elgg_view_entity_icon($owner, $size);
		}
		$container = $entity->getContainerEntity();
		if (!$container instanceof ElggEntity) {
			return;
		}

		$title = $container->getDisplayName();

		if (!$title) {
			$keys = [
				"item:{$container->type}:{$container->getSubtype()}",
				"item:{$container->subtype}",
				"untitled",
			];

			foreach ($keys as $key) {
				if (elgg_language_key_exists($key)) {
					$title = elgg_echo($key);
				}
			}
		}

		$title = elgg_echo('search:comment_on', [$title]);

		$entity->setVolatileData('search_matched_icon', $icon);
		$entity->setVolatileData('search_matched_title', $title);

		return $entity;
	}

}
