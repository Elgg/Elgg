<?php
/**
 * Group profile fields
 */

$group = $vars['entity'];

$profile_fields = elgg_get_config('group');

if (is_array($profile_fields) && count($profile_fields) > 0) {
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

		echo "<div>";
		echo "<b>";
		echo elgg_echo("groups:$key");
		echo ": </b>";
		echo elgg_view("output/$valtype", $options);
		echo "</div>";
	}
}
