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
	
	echo <<<___FIELD
	<div class='clearfix group-profile-field'>
		<div class='elgg-col elgg-col-1of5'>
			<b>{$field_title}:</b>
		</div>
		<div class='elgg-col elgg-col-4of5'>
			{$field_value}
		</div>
	</div>
___FIELD;
}
