<?php

namespace Elgg\Search;

use Elgg\Hook;

/**
 * @access private
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

		$profile_fields = array_keys((array) elgg_get_config('group'));

		$value['metadata'] = array_merge($value['metadata'], $profile_fields);

		return $value;
	}
}
