<?php

namespace Elgg\Search;

/**
 * @internal
 * @since  3.0
 */
class TagsSearchFieldsHandler {

	/**
	 * Populate default search fields for entities
	 *
	 * @param \Elgg\Event $event 'search:fields', 'group|user|object'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Event $event) {

		$value = (array) $event->getValue();

		$defaults = [
			'metadata' => [],
		];

		$value = array_merge($defaults, $value);

		$value['metadata'] = array_merge($value['metadata'], ['tags']);

		return $value;
	}
}
