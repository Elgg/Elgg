<?php
/**
 * Elgg profile plugin edit default profile action removal
 *
 * @package ElggProfile
 */

global $CONFIG;

$id = get_input('id');

$fieldlist = get_plugin_setting('user_defined_fields', 'profile');
if (!$fieldlist) {
	$fieldlist = '';
}

$fieldlist = str_replace("{$id},", "", $fieldlist);
$fieldlist = str_replace(",{$id}", "", $fieldlist);
$fieldlist = str_replace("{$id}", "", $fieldlist);

if (($id) && (set_plugin_setting("admin_defined_profile_$id", '', 'profile')) &&
	(set_plugin_setting("admin_defined_profile_type_$id", '', 'profile')) &&
	set_plugin_setting('user_defined_fields',$fieldlist,'profile')) {
	system_message(elgg_echo('profile:editdefault:delete:success'));
} else {
	register_error(elgg_echo('profile:editdefault:delete:fail'));
}

forward(REFERER);