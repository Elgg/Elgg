<?php
/**
 * Elgg profile plugin edit default profile action
 */

$label = get_input('label');
$type = get_input('type');
$id = get_input('id');

if (!$label || !$type) {
	return elgg_error_response(elgg_echo('profile:editdefault:fail'));
}

if ($id === '') {
	$custom_fields = elgg_get_config('profile_custom_fields');
	$fieldlist = [];
	if (!empty($custom_fields) || ($custom_fields === '0')) {
		$fieldlist = explode(',', $custom_fields);
	}

	$id = (int) count($fieldlist) ? ((int) max($fieldlist) + 1) : 0;

	$fieldlist[] = $id;

	$fieldlist = implode(',', $fieldlist);
	
	if (!elgg_save_config('profile_custom_fields', $fieldlist)) {
		return elgg_error_response(elgg_echo('profile:editdefault:fail'));
	}
}

if (!elgg_save_config("admin_defined_profile_$id", $label) || !elgg_save_config("admin_defined_profile_type_$id", $type)) {
	return elgg_error_response(elgg_echo('profile:editdefault:fail'));
}

return elgg_ok_response('', elgg_echo('profile:editdefault:success'));
