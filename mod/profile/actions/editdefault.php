<?php
/**
 * Elgg profile plugin edit default profile action
 *
 * @package ElggProfile
 */

global $CONFIG;
admin_gatekeeper();

$label = sanitise_string(get_input('label'));
$type = sanitise_string(get_input('type'));

$fieldlist = get_plugin_setting('user_defined_fields', 'profile');
if (!$fieldlist) {
	$fieldlist = '';
}

if (($label) && ($type)){
	// Assign a random name
	$n = md5(time().rand(0,9999));

	if (!empty($fieldlist)) {
		$fieldlist .= ',';
	}
	$fieldlist .= $n;

	if ((set_plugin_setting("admin_defined_profile_$n", $label, 'profile')) &&
		(set_plugin_setting("admin_defined_profile_type_$n", $type, 'profile')) &&
		set_plugin_setting('user_defined_fields',$fieldlist,'profile')) {
		system_message(elgg_echo('profile:editdefault:success'));
	} else {
		register_error(elgg_echo('profile:editdefault:fail'));
	}
} else {
	register_error(elgg_echo('profile:editdefault:fail'));
}

forward(REFERER);