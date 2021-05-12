<?php
/**
 * Edit profile form
 *
 * @tip Use 'profile:fields','profile' hook to modify profile fields configuration.
 * Profile fields are configuration as an array of $shortname => $input_type pairs,
 * where $shortname is the metadata name used to store the value, and the $input_type is
 * an input view used to render the field input element.
 *
 * @uses vars['entity']
 */
$entity = elgg_extract('entity', $vars);
/* @var ElggUser $entity */

echo elgg_view_field([
	'#type' => 'text',
	'name' => 'name',
	'value' => $entity->name,
	'#label' => elgg_echo('user:name:label'),
	'maxlength' => 50, // hard coded in /actions/profile/edit
]);

$sticky_values = elgg_get_sticky_values('profile:edit');

$profile_fields = elgg()->fields->get('user', 'user');
foreach ($profile_fields as $field) {
	$shortname = $field['name'];
	$valtype = $field['#type'];
	
	$annotations = $entity->getAnnotations([
		'annotation_names' => "profile:$shortname",
		'limit' => false,
	]);
	
	$access_id = ACCESS_DEFAULT;
	$value = '';
	
	if ($annotations) {
		foreach ($annotations as $annotation) {
			if (!empty($value)) {
				$value .= ', ';
			}
			$value .= $annotation->value;
			$access_id = $annotation->access_id;
		}
	}

	// sticky form values take precedence over saved ones
	if (isset($sticky_values[$shortname])) {
		$value = $sticky_values[$shortname];
	}
	if (isset($sticky_values['accesslevel'][$shortname])) {
		$access_id = $sticky_values['accesslevel'][$shortname];
	}

	$id = "profile-{$shortname}";
	$input = elgg_view("input/{$valtype}", [
		'name' => $shortname,
		'value' => $value,
		'id' => $id,
	]);
	$access_input = elgg_view('input/access', [
		'name' => "accesslevel[{$shortname}]",
		'value' => $access_id,
	]);
	
	echo elgg_view('elements/forms/field', [
		'input' => elgg_format_element('div', [
				'class' => 'elgg-field-input',
			], $input . $access_input),
		'label' => elgg_view('elements/forms/label', [
			'label' => $field['#label'],
			'id' => $id,
		])
	]);
}

elgg_clear_sticky_form('profile:edit');

echo elgg_view('input/hidden', ['name' => 'guid', 'value' => $entity->guid]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
	'#class' => 'elgg-foot',
]);
elgg_set_form_footer($footer);
