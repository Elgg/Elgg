<?php
/**
 * Group edit form
 *
 * This view contains the group profile field configuration
 */

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('groups:name'),
	'required' => true,
	'name' => 'name',
	'value' => elgg_extract('name', $vars),
]);

echo elgg_view('entity/edit/icon', [
	'entity' => elgg_extract('entity', $vars),
	'entity_type' => 'group',
	'entity_subtype' => 'group',
]);

// show the configured group profile fields
$group_profile_fields = elgg()->fields->get('group', 'group');
foreach ($group_profile_fields as $field) {
	$field['value'] = elgg_extract($field['name'], $vars);
	
	echo elgg_view_field($field);
}
