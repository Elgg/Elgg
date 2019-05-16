<?php

$settings = [
	'security_protect_upgrade',
	'security_protect_cron',
	'security_disable_password_autocomplete',
	'security_email_require_password',
	'security_notify_admins',
	'security_notify_user_admin',
	'security_notify_user_ban',
	'security_notify_user_password',
	'session_bound_entity_icons',
];

foreach ($settings as $setting) {
	elgg_save_config($setting, (bool) get_input($setting));
}

return elgg_ok_response('', elgg_echo('admin:configuration:success'));
