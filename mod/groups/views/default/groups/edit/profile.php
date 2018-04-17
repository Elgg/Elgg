<?php

/**
 * Group edit form
 *
 * This view contains the group profile field configuration
 *
 * @package ElggGroups
 */

$fields = [];

$name = elgg_extract('name', $vars);
$group_profile_fields = (array) elgg_get_config('group');

$fields[] = [
	'#type' => 'file',
	'#label' => elgg_echo('groups:icon'),
	'name' => 'icon',
];

$fields[] = [
	'#type' => 'text',
	'#label' => elgg_echo('groups:name'),
	'required' => true,
	'name' => 'name',
	'value' => $name,
];

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

	$fields[] = $options;
}

$fields = elgg_trigger_plugin_hook('fields:profile', 'group', $vars, $fields);

foreach ($fields as $field) {
	echo elgg_view_field($field);
}
