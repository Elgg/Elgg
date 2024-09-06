<?php
/**
 * Discussion topic add/edit form body
 */

$entity = elgg_extract('entity', $vars);

$container_guid = (int) elgg_extract('container_guid', $vars);
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
		elgg_import_esm('forms/discussion/save');
		echo elgg_view_field([
			'#type' => 'select',
			'#label' => elgg_echo('discussion:topic:container'),
			'#help' => elgg_echo('discussion:topic:container:help'),
			'name' => 'container_guid',
			'options_values' => $options_values,
		]);
		
		$show_container_input = false;
	}
}

$fields = elgg()->fields->get('object', 'discussion');
foreach ($fields as $field) {
	$name = elgg_extract('name', $field);
	if (elgg_extract('#type', $field) === 'access' && $entity instanceof \ElggDiscussion) {
		$field['entity'] = $entity;
	}
	
	if ($name === 'status' && !$entity instanceof \ElggDiscussion) {
		// don't show status dropdown for new discussions
		$field = [
			'#type' => 'hidden',
			'name' => $name,
		];
	}
	
	$field['value'] = elgg_extract($name, $vars);
	echo elgg_view_field($field);
}

if ($entity instanceof \ElggDiscussion) {
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => 'topic_guid',
		'value' => $entity->guid,
	]);
}

if ($show_container_input) {
	echo elgg_view_field([
		'#type' => 'container_guid',
		'value' => $container_guid,
		'entity_type' => 'object',
		'entity_subtype' => 'discussion',
	]);
}

$footer = elgg_view_field([
	'#type' => 'submit',
	'text' => elgg_echo('save'),
]);
elgg_set_form_footer($footer);
