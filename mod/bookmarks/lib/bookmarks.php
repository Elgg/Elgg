<?php
/**
 * Bookmarks helper functions
 *
 * @package Bookmarks
 */

/**
 * Prepare the add/edit form variables
 *
 * @param ElggObject $bookmark A bookmark object.
 * @return array
 */
function bookmarks_prepare_form_vars($bookmark = null) {
	// input names => defaults
	$values = array(
		'title' => get_input('title', ''),
		'address' => get_input('address', ''),
		'description' => '',
		'access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'shares' => array(),
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $bookmark,
	);

	if ($bookmark) {
		foreach (array_keys($values) as $field) {
			$values[$field] = $bookmark->$field;
		}
	}

	if (elgg_is_sticky_form('bookmarks')) {
		foreach (array_keys($values) as $field) {
			$values[$field] = elgg_get_sticky_value('bookmarks', $field);
		}
	}

	elgg_clear_sticky_form('bookmarks');

	return $values;
}
