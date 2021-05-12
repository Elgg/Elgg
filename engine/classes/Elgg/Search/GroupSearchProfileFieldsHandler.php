<?php

namespace Elgg\Search;

use Elgg\Hook;

/**
 * @internal
 * @since  3.0
 */
class GroupSearchProfileFieldsHandler {

	/**
	 * Search through the group profile fields
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

		$profile_fields = elgg()->fields->get('group', 'group');
		foreach ($profile_fields as $field) {
			$value['metadata'][] = $field['name'];
		}

		return $value;
	}
}
