<?php

namespace Elgg\Search;

/**
 * @internal
 * @since  3.0
 */
class GroupSearchProfileFieldsHandler {

	/**
	 * Search through the group profile fields
	 *
	 * @param \Elgg\Event $event 'search:fields', 'group'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Event $event) {
		$value = (array) $event->getValue();

		$defaults = [
			'metadata' => [],
		];

		$value = array_merge($defaults, $value);

		$profile_fields = _elgg_services()->fields->get('group', 'group');
		foreach ($profile_fields as $field) {
			$value['metadata'][] = $field['name'];
		}

		return $value;
	}
}
