<?php

namespace Elgg\Search;

use Elgg\Hook;

/**
 * @internal
 * @since  3.0
 */
class TagsSearchFieldsHandler {

	/**
	 * Populate default search fields for entities
	 *
	 * @elgg_plugin_hook search:fields group|user|object
	 *
	 * @param Hook $hook Hook
	 *
	 * @return array
	 */
	public function __invoke(Hook $hook) {

		$value = (array) $hook->getValue();

		$defaults = [
			'metadata' => [],
		];

		$value = array_merge($defaults, $value);

		$value['metadata'] = array_merge($value['metadata'], ['tags']);

		return $value;
	}
}
