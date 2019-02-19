<?php

namespace Elgg\Search;

use Elgg\Hook;

/**
 * @access private
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

		$profile_fields = array_keys((array) elgg_get_config('profile_fields'));
		array_walk($profile_fields, function(&$value) {
			$value = "profile:{$value}";
		});

		$value['annotations'] = array_merge($value['annotations'], $profile_fields);

		return $value;
	}
}
