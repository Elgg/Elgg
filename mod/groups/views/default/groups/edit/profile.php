<?php

/**
 * Group edit form
 *
 * This view contains the group profile field configuration
 *
 * @package ElggGroups
 */

$name = elgg_extract('name', $vars);
$group_profile_fields = (array) elgg_get_config('group');

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('groups:name'),
	'required' => true,
	'name' => 'name',
	'value' => $name,
]);

echo elgg_view('entity/edit/icon', [
	'entity' => elgg_extract('entity', $vars),
	'entity_type' => 'group',
	'entity_subtype' => 'group',
]);

// show the configured group profile fields
foreach ($group_profile_fields as $shortname => $valtype) {
	$options = [
		'#type' => $valtype,
		'name' => $shortname,
		'value' => elgg_extract($shortname, $vars),
	];
	
	if ($valtype !== 'hidden') {
		$options['#label'] = elgg_echo("groups:{$shortname}");
	}
	
	echo elgg_view_field($options);
}
