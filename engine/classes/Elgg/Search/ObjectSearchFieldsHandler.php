<?php

namespace Elgg\Search;

use Elgg\Hook;

/**
 * @access private
 * @since  3.0
 */
class ObjectSearchFieldsHandler {

	/**
	 * Populate default search fields for object entities
	 *
	 * @elgg_plugin_hook search:fields object
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
			'title',
			'description',
		];

		$value['metadata'] = array_merge($value['metadata'], $fields);

		return $value;
	}
}
