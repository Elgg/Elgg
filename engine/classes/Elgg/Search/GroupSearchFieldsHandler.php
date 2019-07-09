<?php

namespace Elgg\Search;

use Elgg\Hook;

/**
 * @internal
 * @since  3.0
 */
class GroupSearchFieldsHandler {

	/**
	 * Populate default search fields for group entities
	 *
	 * @elgg_plugin_hook search:fields group
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

		$fields = [
			'name',
			'description',
		];

		$value['metadata'] = array_merge($value['metadata'], $fields);

		return $value;
	}
}
