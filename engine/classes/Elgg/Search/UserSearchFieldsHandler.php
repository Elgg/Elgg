<?php

namespace Elgg\Search;

/**
 * @internal
 * @since  3.0
 */
class UserSearchFieldsHandler {

	/**
	 * Populate default search fields for user entities
	 *
	 * @param \Elgg\Event $event 'search:fields', 'user'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Event $event) {

		$value = (array) $event->getValue();

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
