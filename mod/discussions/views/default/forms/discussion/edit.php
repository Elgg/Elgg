<?php
/**
 * Discussion topic add/edit form body
 */

$entity = elgg_extract('entity', $vars);

$fields = (array) elgg_extract('fields', $vars);

$container_guid = (int) elgg_extract('container_guid', $vars);
$container_entity = get_entity($container_guid);
$show_container_input = true;
if (!$entity instanceof \ElggDiscussion && !$container_entity instanceof \ElggGroup) {
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
		elgg_import_esm('forms/discussion/edit');
		array_unshift($fields, [
			'#type' => 'select',
			'#label' => elgg_echo('discussion:topic:container'),
			'#help' => elgg_echo('discussion:topic:container:help'),
			'name' => 'container_guid',
			'options_values' => $options_values,
		]);
		
		$show_container_input = false;
	}
}

foreach ($fields as $index => $field) {
	$name = elgg_extract('name', $field);
	if ($name === 'status' && !$entity instanceof \ElggDiscussion) {
		// don't show status dropdown for new discussions
		$fields[$index] = [
			'#type' => 'hidden',
			'name' => $name,
		];
	}
}

if ($show_container_input) {
	$fields[] = [
		'#type' => 'container_guid',
		'name' => 'container_guid',
		'entity_type' => 'object',
		'entity_subtype' => 'discussion',
	];
}

$vars['fields'] = $fields;

echo elgg_view('forms/entity/edit', $vars);
