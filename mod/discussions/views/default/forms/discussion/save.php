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

$container_entity = get_entity($container_guid);
$show_container_input = true;
if (!$container_entity instanceof \ElggGroup) {
	$options_values = [$container_guid => ''];
	$groups = elgg_get_logged_in_user_entity()->getGroups([
		'limit' => false,
		'batch' => true,
		'sort_by' => [
			'property' => 'name',
			'direction' => 'asc',
		],
	]);
	foreach ($groups as $group) {
		if (!$group->isToolEnabled('forum')) {
			continue;
		}
		
		$options_values[$group->guid] = $group->getDisplayName();
	}
	
	if (count($options_values) > 1) {
		elgg_require_js('forms/discussion/save');
		echo elgg_view_field([
			'#type' => 'select',
			'#label' => elgg_echo('discussion:topic:container'),
			'#help' => elgg_echo('discussion:topic:container:help'),
			'name' => 'container_guid',
			'options_values' => $options_values
		]);
		
		$show_container_input = false;
	}
}

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('title'),
	'name' => 'title',
	'value' => $title,
	'required' => true,
]);

echo elgg_view_field([
	'#type' => 'longtext',
	'#label' => elgg_echo('discussion:topic:description'),
	'name' => 'description',
	'value' => $desc,
	'required' => true,
	'editor_type' => 'simple',
]);

echo elgg_view_field([
	'#type' => 'tags',
	'#label' => elgg_echo('tags'),
	'name' => 'tags',
	'value' => $tags,
]);

if (!empty($guid)) {
	echo elgg_view_field([
		'#type' => 'select',
		'#label' => elgg_echo('discussion:topic:status'),
		'name' => 'status',
		'value' => $status,
		'options_values' => [
			'open' => elgg_echo('status:open'),
			'closed' => elgg_echo('status:closed'),
		],
	]);
}

echo elgg_view_field([
	'#type' => 'access',
	'#label' => elgg_echo('access'),
	'#class' => 'discussion-access',
	'name' => 'access_id',
	'value' => $access_id,
	'entity' => get_entity($guid),
	'entity_type' => 'object',
	'entity_subtype' => 'discussion',
]);

if ($show_container_input) {
	echo elgg_view_field([
		'#type' => 'container_guid',
		'value' => $container_guid,
		'entity_type' => 'object',
		'entity_subtype' => 'discussion',
	]);
}

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
