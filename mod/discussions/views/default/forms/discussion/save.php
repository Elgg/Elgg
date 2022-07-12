<?php

/**
 * Discussion topic add/edit form body
 *
 */
$title = elgg_extract('title', $vars, '');
$desc = elgg_extract('description', $vars, '');
$status = elgg_extract('status', $vars, '');
$tags = elgg_extract('tags', $vars, '');
$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
$container_guid = elgg_extract('container_guid', $vars);
$guid = elgg_extract('guid', $vars, null);

echo elgg_view_field([
	'#type' => 'text',
	'name' => 'title',
	'value' => $title,
	'#label' => elgg_echo('title'),
	'required' => true,
]);

echo elgg_view_field([
	'#type' => 'longtext',
	'name' => 'description',
	'value' => $desc,
	'#label' => elgg_echo('discussion:topic:description'),
	'required' => true,
	'editor_type' => 'simple',
]);

echo elgg_view_field([
	'#type' => 'tags',
	'name' => 'tags',
	'value' => $tags,
	'#label' => elgg_echo('tags'),
]);

if (!empty($guid)) {
	echo elgg_view_field([
		'#type' => 'select',
		'name' => 'status',
		'value' => $status,
		'options_values' => [
			'open' => elgg_echo('status:open'),
			'closed' => elgg_echo('status:closed'),
		],
		'#label' => elgg_echo('discussion:topic:status'),
	]);
}

echo elgg_view_field([
	'#type' => 'access',
	'name' => 'access_id',
	'value' => $access_id,
	'entity' => get_entity($guid),
	'entity_type' => 'object',
	'entity_subtype' => 'discussion',
	'#label' => elgg_echo('access'),
]);

echo elgg_view_field([
	'#type' => 'container_guid',
	'value' => $container_guid,
	'entity_type' => 'object',
	'entity_subtype' => 'discussion',
]);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'topic_guid',
	'value' => $guid,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);
elgg_set_form_footer($footer);
