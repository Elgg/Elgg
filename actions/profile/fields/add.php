<?php
/**
 * Elgg profile plugin edit default profile action
 *
 * @package ElggProfile
 */

$label = get_input('label');
$type = get_input('type');

$fieldlist = elgg_get_config('profile_custom_fields');
if (!$fieldlist) {
	$fieldlist = '';
	$id = 1;
} else {
	$fieldlistarray = explode(',', $fieldlist);
	foreach ($fieldlistarray as $key => $value) {
		$fieldlistarray[$key] = (int)$value;
	}
	$id = max($fieldlistarray) + 1;
}

if (($label) && ($type)) {
	if (!empty($fieldlist)) {
		$fieldlist .= ',';
	}
	$fieldlist .= "$id";

	if (elgg_save_config("admin_defined_profile_$id", $label) &&
		elgg_save_config("admin_defined_profile_type_$id", $type) &&
		elgg_save_config('profile_custom_fields', $fieldlist)) {

		system_message(elgg_echo('profile:editdefault:success'));
	} else {
		register_error(elgg_echo('profile:editdefault:fail'));
	}
} else {
	register_error(elgg_echo('profile:editdefault:fail'));
}

forward(REFERER);