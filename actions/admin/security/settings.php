<?php

// checkboxes (on/off)
$bool_settings = [
	'security_protect_upgrade',
	'security_protect_cron',
	'security_disable_password_autocomplete',
	'security_email_require_password',
	'security_email_require_confirmation',
	'security_notify_admins',
	'security_notify_user_admin',
	'security_notify_user_ban',
	'security_notify_user_password',
	'session_bound_entity_icons',
];

foreach ($bool_settings as $setting) {
	elgg_save_config($setting, (bool) get_input($setting));
}

// integer settings
$int_settings = [
	'minusername',
	'min_password_length',
	'min_password_lower',
	'min_password_upper',
	'min_password_number',
	'min_password_special',
];

foreach ($int_settings as $setting) {
	$value = get_input($setting);
	if (elgg_is_empty($value)) {
		// input was left empty ('') so remove config setting
		elgg_remove_config($setting);
	} else {
		elgg_save_config($setting, (int) $value);
	}
}

return elgg_ok_response('', elgg_echo('admin:configuration:success'));
