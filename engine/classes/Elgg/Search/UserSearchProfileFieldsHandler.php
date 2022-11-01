<?php

namespace Elgg\Search;

/**
 * @internal
 * @since  3.0
 */
class UserSearchProfileFieldsHandler {

	/**
	 * Search through the user profile fields
	 *
	 * @param \Elgg\Event $event 'search:fields', 'user'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Event $event) {

		$value = (array) $event->getValue();

		$defaults = [
			'annotations' => [],
		];

		$value = array_merge($defaults, $value);

		$profile_fields = _elgg_services()->fields->get('user', 'user');
		foreach ($profile_fields as $field) {
			$value['annotations'][] = "profile:{$field['name']}";
		}

		return $value;
	}
}
