<?php

namespace Elgg\Search;

use Elgg\Hook;

/**
 * @internal
 * @since  3.0
 */
class UserSearchProfileFieldsHandler {

	/**
	 * Search through the user profile fields
	 *
	 * @elgg_plugin_hook search:fields user
	 *
	 * @param Hook $hook Hook
	 *
	 * @return array
	 */
	public function __invoke(Hook $hook) {

		$value = (array) $hook->getValue();

		$defaults = [
			'annotations' => [],
		];

		$value = array_merge($defaults, $value);

		$profile_fields = elgg()->fields->get('user', 'user');
		foreach ($profile_fields as $field) {
			$value['annotations'][] = "profile:{$field['name']}";
		}

		return $value;
	}
}
