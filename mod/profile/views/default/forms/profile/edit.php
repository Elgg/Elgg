<?php
/**
 * Edit profile form
 *
 * @tip Use 'fields', 'user:user' event to modify profile fields configuration.
 *
 * @uses vars['entity']
 */

/* @var ElggUser $entity */
$entity = elgg_extract('entity', $vars);

echo elgg_view('forms/profile/edit/name', $vars);

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
	'text' => elgg_echo('save'),
]);
elgg_set_form_footer($footer);
