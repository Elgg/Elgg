<?php

namespace Elgg\Search;

use Elgg\Hook;

/**
 * @access private
 * @since  3.0
 */
class UserSearchFieldsHandler {

	/**
	 * Populate default search fields for user entities
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
			'metadata' => [],
			'annotations' => [],
		];

		$value = array_merge($defaults, $value);

		$fields = [
			'username',
			'name',
			'description',
		];

		$tags = (array) elgg_get_registered_tag_metadata_names();

		$value['metadata'] = array_merge($value['metadata'], $fields, $tags);

		$profile_fields = array_keys((array) elgg_get_config('profile_fields'));

		$value['annotations'] = array_merge($value['annotations'], $profile_fields);

		return $value;
	}
}
