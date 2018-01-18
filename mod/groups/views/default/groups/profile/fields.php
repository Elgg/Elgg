<?php
/**
 * Group profile fields
 */

$group = elgg_extract('entity', $vars);
if (!$group instanceof ElggGroup) {
	return;
}

$profile_fields = elgg_get_config('group');
if (empty($profile_fields) || !is_array($profile_fields)) {
	return;
}

$output = '';
foreach ($profile_fields as $key => $valtype) {
	// do not show the name
	if ($key == 'name') {
		continue;
	}

	$value = $group->$key;
	if (is_null($value)) {
		continue;
	}

	$options = ['value' => $group->$key];
	if ($valtype == 'tags') {
		$options['tag_names'] = $key;
	}

	$field_title = elgg_echo("groups:{$key}");
	$field_value = elgg_view("output/$valtype", $options);
	$field_value = elgg_format_element('span', [], $field_value);

	$output .= elgg_view('object/elements/field', [
		'label' => $field_title,
		'value' => $field_value,
		'class' => 'group-profile-field',
		'name' => $key,
	]);
}

if ($output) {
	echo elgg_format_element('div', [
		'class' => 'elgg-profile-fields',
	], $output);
}
