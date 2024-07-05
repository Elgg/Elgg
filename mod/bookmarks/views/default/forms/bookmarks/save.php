<?php
/**
 * Edit / add a bookmark
 */

$entity = elgg_extract('entity', $vars);

$fields = elgg()->fields->get('object', 'bookmarks');
foreach ($fields as $field) {
	$name = elgg_extract('name', $field);
	
	if (elgg_extract('#type', $field) === 'access' && $entity instanceof \ElggBookmark) {
		$field['entity'] = $entity;
	}
	
	$field['value'] = elgg_extract($name, $vars);
	echo elgg_view_field($field);
}

if ($entity instanceof \ElggBookmark) {
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => 'guid',
		'value' => $entity->guid,
	]);
}

echo elgg_view_field([
	'#type' => 'container_guid',
	'value' => elgg_extract('container_guid', $vars),
	'entity_type' => 'object',
	'entity_subtype' => 'bookmarks',
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'text' => elgg_echo('save'),
]);
elgg_set_form_footer($footer);
