<?php
/**
 * Holds helper functions for file plugin
 */

/**
 * Adds a toggle to filter menu for switching between list and gallery views
 *
 * @return void
 */
function file_register_toggle() {

	if (get_input('list_type', 'list') == 'list') {
		$list_type = 'gallery';
		$icon = elgg_view_icon('grid');
	} else {
		$list_type = 'list';
		$icon = elgg_view_icon('list');
	}

	$url = elgg_http_add_url_query_elements(current_page_url(), ['list_type' => $list_type]);
	
	elgg_register_menu_item('filter:filter', [
		'name' => 'file_list',
		'text' => $icon,
		'href' => $url,
		'title' => elgg_echo("file:list:$list_type"),
		'priority' => 1000,
	]);
}

/**
 * Prepare the upload/edit form variables
 *
 * @param ElggFile $file the file to edit
 *
 * @return array
 */
function file_prepare_form_vars($file = null) {

	// input names => defaults
	$values = [
		'title' => '',
		'description' => '',
		'access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $file,
	];

	if ($file) {
		foreach (array_keys($values) as $field) {
			if (isset($file->$field)) {
				$values[$field] = $file->$field;
			}
		}
	}

	if (elgg_is_sticky_form('file')) {
		$sticky_values = elgg_get_sticky_values('file');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('file');

	return $values;
}
