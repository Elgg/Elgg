<?php

/**
 * Group edit form
 *
 * This view contains the group profile field configuration
 *
 * @package ElggGroups
 */

$name = elgg_extract('name', $vars);
$group_profile_fields = elgg_get_config('group');

echo elgg_view_input('file', [
	'name' => 'icon',
	'label' => elgg_echo('groups:icon'),
]);
echo elgg_view_input('text', [
	'name' => 'name',
	'value' => $name,
	'label' => elgg_echo('groups:name'),
	'required' => true,
]);

// show the configured group profile fields
foreach ((array)$group_profile_fields as $shortname => $valtype) {
	$value = elgg_extract($shortname, $vars);

	if ($valtype == 'hidden') {
		echo elgg_view("input/{$valtype}", [
			'name' => $shortname,
			'value' => $value,
		]);
		continue;
	}

	echo elgg_view_input($valtype, [
		'name' => $shortname,
		'value' => $value,
		'label' => elgg_echo("groups:{$shortname}"),
	]);
}