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

$fields = [
	[
		'type' => 'text',
		'name' => 'title',
		'value' => $title,
		'label' => elgg_echo('title'),
		'required' => true,
	],
	[
		'type' => 'longtext',
		'name' => 'description',
		'value' => $desc,
		'label' => elgg_echo('discussion:topic:description'),
		'required' => true,
	],
	[
		'type' => 'tags',
		'name' => 'tags',
		'value' => $tags,
		'label' => elgg_echo('tags'),
	],
	[
		'type' => 'select',
		'name' => 'status',
		'value' => $status,
		'options_values' => array(
			'open' => elgg_echo('status:open'),
			'closed' => elgg_echo('status:closed'),
		),
		'label' => elgg_echo('discussion:topic:status'),
	],
	[
		'type' => 'access',
		'name' => 'access_id',
		'value' => $access_id,
		'entity' => get_entity($guid),
		'entity_type' => 'object',
		'entity_subtype' => 'discussion',
		'label' => elgg_echo('access'),
	],
	[
		'type' => 'hidden',
		'name' => 'container_guid',
		'value' => $container_guid,
	],
	[
		'type' => 'hidden',
		'name' => 'topic_guid',
		'value' => $guid,
	],
	[
		'type' => 'submit',
		'value' => elgg_echo('save'),
		'field_class' => 'elgg-foot',
	]
];

foreach ($fields as $field) {
	$type = elgg_extract('type', $field, 'text');
	unset($field['type']);
	echo elgg_view_input($type, $field);
}
