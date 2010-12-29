<?php
/**
 * Elgg profile plugin edit default profile action removal
 *
 * @package ElggProfile
 */

$id = get_input('id');

$fieldlist = elgg_get_config('profile_custom_fields');
if (!$fieldlist) {
	$fieldlist = '';
}

$fieldlist = str_replace("{$id},", "", $fieldlist);
$fieldlist = str_replace(",{$id}", "", $fieldlist);
$fieldlist = str_replace("{$id}", "", $fieldlist);

if ($id &&
	unset_config("admin_defined_profile_$id") &&
	unset_config("admin_defined_profile_type_$id") &&
	elgg_save_config('profile_custom_fields', $fieldlist)) {
	
	system_message(elgg_echo('profile:editdefault:delete:success'));
} else {
	register_error(elgg_echo('profile:editdefault:delete:fail'));
}

forward(REFERER);