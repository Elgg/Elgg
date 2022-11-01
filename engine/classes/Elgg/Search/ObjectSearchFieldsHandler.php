<?php

namespace Elgg\Search;

/**
 * @internal
 * @since  3.0
 */
class ObjectSearchFieldsHandler {

	/**
	 * Populate default search fields for object entities
	 *
	 * @param \Elgg\Event $event 'search:fields', 'object'
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
			'title',
			'description',
		];

		$value['metadata'] = array_merge($value['metadata'], $fields);

		return $value;
	}
}
