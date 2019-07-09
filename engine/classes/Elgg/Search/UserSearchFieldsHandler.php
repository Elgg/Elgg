<?php

namespace Elgg\Search;

use Elgg\Hook;

/**
 * @internal
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
		];

		$value = array_merge($defaults, $value);

		$fields = [
			'username',
			'name',
			'description',
		];
		
		if (elgg_in_context('admin') && elgg_is_admin_logged_in()) {
			$fields[] = 'email';
		}

		$value['metadata'] = array_merge($value['metadata'], $fields);

		return $value;
	}
}
