<?php
/**
 * Security settings subview - notification related
 *
 * @since 3.2
 */

$notifications = '';

// notify admins about add/remove of another admin
$notifications .= elgg_view_field([
	'#type' => 'switch',
	'#label' => elgg_echo('admin:security:settings:notify_admins'),
	'#help' => elgg_echo('admin:security:settings:notify_admins:help'),
	'name' => 'security_notify_admins',
	'value' => elgg_get_config('security_notify_admins'),
]);

// notify user about add/remove admin of his/her account
$notifications .= elgg_view_field([
	'#type' => 'switch',
	'#label' => elgg_echo('admin:security:settings:notify_user_admin'),
	'#help' => elgg_echo('admin:security:settings:notify_user_admin:help'),
	'name' => 'security_notify_user_admin',
	'value' => elgg_get_config('security_notify_user_admin'),
]);

// notify user about password change
$notifications .= elgg_view_field([
	'#type' => 'switch',
	'#label' => elgg_echo('admin:security:settings:notify_user_password'),
	'#help' => elgg_echo('admin:security:settings:notify_user_password:help'),
	'name' => 'security_notify_user_password',
	'value' => elgg_get_config('security_notify_user_password'),
]);

// notify user about (un)ban of his/her account
$notifications .= elgg_view_field([
	'#type' => 'switch',
	'#label' => elgg_echo('admin:security:settings:notify_user_ban'),
	'#help' => elgg_echo('admin:security:settings:notify_user_ban:help'),
	'name' => 'security_notify_user_ban',
	'value' => elgg_get_config('security_notify_user_ban'),
]);

// allow others to extend this section
$notifications .= elgg_view('admin/security/settings/extend/notification');

echo elgg_view_module('info', elgg_echo('admin:security:settings:label:notifications'), $notifications);
