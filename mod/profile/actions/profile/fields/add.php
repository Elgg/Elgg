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
	$fieldlist = elgg_get_config('profile_custom_fields');
	if (!$fieldlist && $fieldlist !== '0') {
		$fieldlist = '';
	} else {
		$fieldlistarray = explode(',', $fieldlist);
		foreach ($fieldlistarray as $key => $value) {
			$fieldlistarray[$key] = (int) $value;
		}
		$id = max($fieldlistarray) + 1;
	}
	
	if ($fieldlist !== '') {
		$fieldlist .= ',';
	}
	$fieldlist .= "$id";
	
	if (!elgg_save_config('profile_custom_fields', $fieldlist)) {
		return elgg_error_response(elgg_echo('profile:editdefault:fail'));
	}
}

if (!elgg_save_config("admin_defined_profile_$id", $label) || !elgg_save_config("admin_defined_profile_type_$id", $type)) {
	return elgg_error_response(elgg_echo('profile:editdefault:fail'));
}

return elgg_ok_response('', elgg_echo('profile:editdefault:success'));
