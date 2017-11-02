<?php
/**
 * Elgg profile plugin edit default profile action removal
 *
 */

$id = get_input('id');
if (!is_numeric($id)) {
	return elgg_error_response(elgg_echo('profile:editdefault:delete:fail'));
}

$fieldlist = elgg_get_config('profile_custom_fields') ?: '';

$fieldlist = str_replace("{$id},", "", $fieldlist);
$fieldlist = str_replace(",{$id}", "", $fieldlist);
$fieldlist = str_replace("{$id}", "", $fieldlist);

$remove_profile = elgg_remove_config("admin_defined_profile_$id");
$remove_profile_type = elgg_remove_config("admin_defined_profile_type_$id");
$save_profile_fields = elgg_save_config('profile_custom_fields', $fieldlist);

if ($remove_profile && $remove_profile_type && $save_profile_fields) {
	return elgg_ok_response('', elgg_echo('profile:editdefault:delete:success'));
} else {
	return elgg_error_response(elgg_echo('profile:editdefault:delete:fail'));
}
