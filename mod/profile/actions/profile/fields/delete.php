<?php
/**
 * Elgg profile plugin edit default profile action removal
 *
 */

$id = get_input('id');

$fieldlist = elgg_get_config('profile_custom_fields') ?: '';

$fieldlist = str_replace("{$id},", "", $fieldlist);
$fieldlist = str_replace(",{$id}", "", $fieldlist);
$fieldlist = str_replace("{$id}", "", $fieldlist);

if ($id &&
	elgg_remove_config("admin_defined_profile_$id") &&
	elgg_remove_config("admin_defined_profile_type_$id") &&
	elgg_save_config('profile_custom_fields', $fieldlist)) {

	return elgg_ok_response('', elgg_echo('profile:editdefault:delete:success'));
} else {
	return elgg_error_response(elgg_echo('profile:editdefault:delete:fail'));
}
