<?php
/**
 * List the latest uploads from CKEditor
 */

echo elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'ckeditor_upload',
	'list_type' => 'gallery',
	'limit' => 10,
	'no_results' => elgg_echo('ckeditor:uploads:none'),
));
