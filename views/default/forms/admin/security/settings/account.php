<?php
/**
 * Security settings subview - user account related
 *
 * @since 3.2
 */

$account = '';

// require password the changing email address
$account .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('admin:security:settings:email_require_password'),
	'#help' => elgg_echo('admin:security:settings:email_require_password:help'),
	'name' => 'security_email_require_password',
	'default' => 0,
	'value' => 1,
	'switch' => true,
	'checked' => (bool) elgg_get_config('security_email_require_password'),
]);

// require confirmation on e-mail change
$account .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('admin:security:settings:email_require_confirmation'),
	'#help' => elgg_echo('admin:security:settings:email_require_confirmation:help'),
	'name' => 'security_email_require_confirmation',
	'default' => 0,
	'value' => 1,
	'switch' => true,
	'checked' => (bool) elgg_get_config('security_email_require_confirmation'),
]);

// minimal username length
$account .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('admin:security:settings:minusername'),
	'#help' => elgg_echo('admin:security:settings:minusername:help'),
	'name' => 'minusername',
	'min' => 1,
	'value' => (int) elgg_get_config('minusername'),
	'required' => true,
]);

// minimal password length
$account .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('admin:security:settings:min_password_length'),
	'#help' => elgg_echo('admin:security:settings:min_password_length:help'),
	'name' => 'min_password_length',
	'min' => 1,
	'value' => (int) elgg_get_config('min_password_length'),
	'required' => true,
]);

// minimal password num chars - lower case
$account .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('admin:security:settings:min_password_lower'),
	'#help' => elgg_echo('admin:security:settings:min_password_lower:help'),
	'name' => 'min_password_lower',
	'min' => 0,
	'value' => elgg_get_config('min_password_lower'),
]);

// minimal password num chars - upper case
$account .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('admin:security:settings:min_password_upper'),
	'#help' => elgg_echo('admin:security:settings:min_password_upper:help'),
	'name' => 'min_password_upper',
	'min' => 0,
	'value' => elgg_get_config('min_password_upper'),
]);

// minimal password num chars - lower case
$account .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('admin:security:settings:min_password_number'),
	'#help' => elgg_echo('admin:security:settings:min_password_number:help'),
	'name' => 'min_password_number',
	'min' => 0,
	'value' => elgg_get_config('min_password_number'),
]);

// minimal password num chars - lower case
$account .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('admin:security:settings:min_password_special'),
	'#help' => elgg_echo('admin:security:settings:min_password_special:help'),
	'name' => 'min_password_special',
	'min' => 0,
	'value' => elgg_get_config('min_password_special'),
]);

// allow others to extend this section
$account .= elgg_view('admin/security/settings/extend/account');

echo elgg_view_module('info', elgg_echo('admin:security:settings:label:account'), $account);
