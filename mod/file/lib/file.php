<?php
/**
 * File helper functions
 *
 * @package ElggFile
 */

/**
 * Prepare the upload/edit form variables
 *
 * @param FilePluginFile $file
 * @return array
 */
function file_prepare_form_vars($file = null) {

	// input names => defaults
	$values = array(
		'title' => '',
		'description' => '',
		'access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $file,
	);

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
