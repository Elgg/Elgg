<?php

namespace Elgg\Bookmarks\Forms;

/**
 * Prepare the fields for the bookmarks/save form
 *
 * @since 5.0
 */
class PrepareFields {
	
	/**
	 * Prepare fields
	 *
	 * @param \Elgg\Event $event 'form:prepare:fields', 'bookmarks/save'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Event $event): array {
		$vars = $event->getValue();
		
		// input names => defaults
		$values = [
			'title' => (string) get_input('title'), // bookmarklet support
			'address' => (string) get_input('address'),
			'description' => '',
			'access_id' => ACCESS_DEFAULT,
			'tags' => '',
			'container_guid' => elgg_get_page_owner_guid(),
			'guid' => null,
		];
		
		$bookmark = elgg_extract('entity', $vars);
		if ($bookmark instanceof \ElggBookmark) {
			// load current bookmark values
			foreach (array_keys($values) as $field) {
				if (isset($bookmark->$field)) {
					$values[$field] = $bookmark->$field;
				}
			}
		}
		
		return array_merge($values, $vars);
	}
}
