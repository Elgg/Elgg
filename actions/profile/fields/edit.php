<?php
/**
 * Edit a custom profile field
 */

$id = get_input('id');
$label = get_input('label');

if (!elgg_get_config("admin_defined_profile_$id")) {
	register_error(elgg_echo('profile:editdefault:fail'));
	forward(REFERER);
}

if (elgg_save_config("admin_defined_profile_$id", $label)) {
	system_message(elgg_echo('profile:editdefault:success'));
} else {
	register_error(elgg_echo('profile:editdefault:fail'));
}

forward(REFERER);