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

echo elgg_view_input('text', array(
	'name' => 'name',
	'value' => $entity->name,
	'label' => elgg_echo('user:name:label'),
	'maxlength' => 50, // hard coded in /actions/profile/edit
));

$sticky_values = elgg_get_sticky_values('profile:edit');

$profile_fields = elgg_get_config('profile_fields');
if (is_array($profile_fields) && count($profile_fields) > 0) {
	foreach ($profile_fields as $shortname => $valtype) {
		$metadata = elgg_get_metadata(array(
			'guid' => $entity->guid,
			'metadata_name' => $shortname,
			'limit' => false
		));
		if ($metadata) {
			if (is_array($metadata)) {
				$value = '';
				foreach ($metadata as $md) {
					if (!empty($value)) {
						$value .= ', ';
					}
					$value .= $md->value;
					$access_id = $md->access_id;
				}
			} else {
				$value = $metadata->value;
				$access_id = $metadata->access_id;
			}
		} else {
			$value = '';
			$access_id = ACCESS_DEFAULT;
		}

		// sticky form values take precedence over saved ones
		if (isset($sticky_values[$shortname])) {
			$value = $sticky_values[$shortname];
		}
		if (isset($sticky_values['accesslevel'][$shortname])) {
			$access_id = $sticky_values['accesslevel'][$shortname];
		}

		$id = "profile-$shortname";
		$input = elgg_view("input/$valtype", [
			'name' => $shortname,
			'value' => $value,
			'id' => $id,
		]);
		$access_input = elgg_view('input/access', [
			'name' => "accesslevel[$shortname]",
			'value' => $access_id,
		]);

		echo elgg_view('elements/forms/field', [
			'input' => $input . $access_input,
			'label' => elgg_view('elements/forms/label', [
				'label' => elgg_echo("profile:$shortname"),
				'id' => $id,
			])
		]);
	}
}

elgg_clear_sticky_form('profile:edit');

echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $entity->guid));
echo elgg_view_input('submit', [
	'value' => elgg_echo('save'),
	'field_class' => 'elgg-foot',
]);
