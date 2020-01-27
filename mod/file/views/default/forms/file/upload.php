<?php
/**
 * Elgg file upload/save form
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

$categories_field = $vars;
$categories_field['#type'] = 'categories';

$fields = [
	[
		'#type' => 'file',
		'#label' => $file_label,
		'name' => 'upload',
		'value' => ($guid),
		'required' => (!$guid),
	],
	[
		'#type' => 'text',
		'#label' => elgg_echo('title'),
		'name' => 'title',
		'value' => $title,
	],
	[
		'#type' => 'longtext',
		'#label' => elgg_echo('description'),
		'name' => 'description',
		'value' => $desc,
		'editor_type' => 'simple',
	],
	[
		'#type' => 'tags',
		'#label' => elgg_echo('tags'),
		'name' => 'tags',
		'value' => $tags,
	],
	$categories_field,
	[
		'#type' => 'access',
		'#label' => elgg_echo('access'),
		'name' => 'access_id',
		'value' => $access_id,
		'entity' => get_entity($guid),
		'entity_type' => 'object',
		'entity_subtype' => 'file',
	],
	[
		'#type' => 'hidden',
		'name' => 'container_guid',
		'value' => $container_guid,
	],
	[
		'#type' => 'hidden',
		'name' => 'file_guid',
		'value' => $guid,
	],
];

foreach ($fields as $field) {
	echo elgg_view_field($field);
}

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => $submit_label,
]);
elgg_set_form_footer($footer);
