<?php
/**
 * Elgg profile plugin edit default profile action removal
 *
 */

$id = get_input('id');
if (!is_numeric($id)) {
	return elgg_error_response(elgg_echo('profile:editdefault:delete:fail'));
}

$fieldlist = elgg_get_config('profile_custom_fields');
if (!$fieldlist) {
	$fieldlist = '';
}

$fieldlist = str_replace("{$id},", "", $fieldlist);
$fieldlist = str_replace(",{$id}", "", $fieldlist);
$fieldlist = str_replace("{$id}", "", $fieldlist);

if (unset_config("admin_defined_profile_$id") &&
	unset_config("admin_defined_profile_type_$id") &&
	elgg_save_config('profile_custom_fields', $fieldlist)) {
	
	return elgg_ok_response('', elgg_echo('profile:editdefault:delete:success'));
}

return elgg_error_response(elgg_echo('profile:editdefault:delete:fail'));
