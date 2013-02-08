<?php
/**
 * List the latest uploads from CKEditor
 */

$content = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'ckeditor_upload',
	'list_type' => 'gallery',
	'limit' => 20,
));
// ticket for 1.9 to add default text if no content
if (!$content) {
	echo '<div />';
}
echo $content;
