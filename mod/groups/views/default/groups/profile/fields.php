<?php
/**
 * Group profile fields
 */

$group = elgg_extract('entity', $vars);
if (!$group instanceof \ElggGroup) {
	return;
}

$profile_fields = elgg()->fields->get('group', 'group');

$output = '';
foreach ($profile_fields as $field) {
	$field_name = $field['name'];
	$field_type = $field['#type'];

	// do not show the name
	if ($field_name == 'name') {
		continue;
	}

	$value = $group->$field_name;
	if (is_null($value)) {
		continue;
	}

	$options = ['value' => $group->$field_name];
	if ($field_type == 'tags') {
		$options['tag_names'] = $field_name;
	}

	$field_value = elgg_view("output/{$field_type}", $options);
	$field_value = elgg_format_element('span', [], $field_value);

	$output .= elgg_view('object/elements/field', [
		'label' => $field['#label'],
		'value' => $field_value,
		'class' => 'group-profile-field',
		'name' => $field_name,
	]);
}

if (empty($output)) {
	return;
}

echo elgg_format_element('div', ['class' => 'elgg-profile-fields'], $output);
