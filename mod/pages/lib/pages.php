<?php
/**
 * Pages function library
 */

/**
 * Prepare the add/edit form variables
 *
 * @param ElggOobject $page
 * @return array
 */
function pages_prepare_form_vars($page = null) {

	// input names => defaults
	$values = array(
		'title' => '',
		'description' => '',
		'access_id' => ACCESS_DEFAULT,
		'write_access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $page,
	);

	if ($page) {
		foreach (array_keys($values) as $field) {
			$values[$field] = $file->$field;
		}
	}

	if (elgg_is_sticky_form('page')) {
		foreach (array_keys($values) as $field) {
			$values[$field] = elgg_get_sticky_value('page', $field);
		}
	}

	elgg_clear_sticky_form('page');

	return $values;
}
