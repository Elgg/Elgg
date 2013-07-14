<?php
/**
 * List the latest uploads from CKEditor
 *
 * Supports passing a guid for a single upload.
 */

$options = array(
	'type' => 'object',
	'subtype' => 'ckeditor_upload',
	'list_type' => 'gallery',
	'limit' => 10,
	'no_results' => elgg_echo('ckeditor:uploads:none'),
);

$guid = get_input('guid');
if ($guid) {
	$options['guid'] = $guid;
}

echo elgg_list_entities($options);
