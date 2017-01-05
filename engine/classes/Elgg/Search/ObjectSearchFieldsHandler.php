<?php

namespace Elgg\Search;

/**
 * @access private
 * @since 3.0
 */
class ObjectSearchFieldsHandler {

	/**
	 * Populate default search fields for user entities
	 *
	 * @elgg_plugin_hook search:fields object
	 *
	 * @param \Elgg\Hook $hook Hook
	 * @return ElggEntity[]|int|false
	 */
	public function __invoke(\Elgg\Hook $hook) {

		$value = (array) $hook->getValue();

		$fields = [
			'title',
			'description',
		];

		$tags = (array) elgg_get_registered_tag_metadata_names();

		return array_unique(array_merge($value, $fields, $tags));
	}
}
