<?php

namespace Elgg\Search;

/**
 * @internal
 * @since  3.0
 */
class GroupSearchFieldsHandler {

	/**
	 * Populate default search fields for group entities
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

		$fields = [
			'name',
			'description',
		];

		$value['metadata'] = array_merge($value['metadata'], $fields);

		return $value;
	}
}
