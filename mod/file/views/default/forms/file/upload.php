<?php

/**
 * Elgg file upload/save form
 *
 * @package ElggFile
 */
// once elgg_view stops throwing all sorts of junk into $vars, we can use 
$title = elgg_extract('title', $vars, '');
$desc = elgg_extract('description', $vars, '');
$tags = elgg_extract('tags', $vars, '');
$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
$container_guid = elgg_extract('container_guid', $vars);
if (!$container_guid) {
	$container_guid = elgg_get_logged_in_user_guid();
}
$guid = elgg_extract('guid', $vars, null);

if ($guid) {
	$file_label = elgg_echo("file:replace");
	$submit_label = elgg_echo('save');
} else {
	$file_label = elgg_echo("file:file");
	$submit_label = elgg_echo('upload');
}

// Get post_max_size and upload_max_filesize
$post_max_size = elgg_get_ini_setting_in_bytes('post_max_size');
$upload_max_filesize = elgg_get_ini_setting_in_bytes('upload_max_filesize');

// Determine the correct value
$max_upload = $upload_max_filesize > $post_max_size ? $post_max_size : $upload_max_filesize;

$upload_limit = elgg_echo('file:upload_limit', array(elgg_format_bytes($max_upload)));

$fields = [
	[
		'type' => 'file',
		'name' => 'upload',
		'label' => $file_label,
		'help' => $upload_limit,
		'value' => ($guid),
		'required' => (!$guid),
	],
	[
		'type' => 'text',
		'name' => 'title',
		'value' => $title,
		'label' => elgg_echo('title'),
	],
	[
		'type' => 'longtext',
		'name' => 'description',
		'value' => $desc,
		'label' => elgg_echo('description'),
	],
	[
		'type' => 'tags',
		'name' => 'tags',
		'value' => $tags,
		'label' => elgg_echo('tags'),
	],
	[
		'type' => 'categories',
	],
	[
		'type' => 'access',
		'name' => 'access_id',
		'value' => $access_id,
		'entity' => get_entity($guid),
		'entity_type' => 'object',
		'entity_subtype' => 'file',
		'label' => elgg_echo('access'),
	],
	[
		'type' => 'hidden',
		'name' => 'container_guid',
		'value' => $container_guid,
	],
	[
		'type' => 'hidden',
		'name' => 'file_guid',
		'value' => $guid,
	],
	[
		'type' => 'submit',
		'value' => $submit_label,
		'field_class' => 'elgg-foot',
	]
];

foreach ($fields as $field) {
	$type = elgg_extract('type', $field, 'text');
	unset($field['type']);
	if ($type == 'categories') {
		$field = $vars;
	}
	echo elgg_view_input($type, $field);
}
