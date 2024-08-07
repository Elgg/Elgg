<?php

$result = elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('installation:registration:label'),
	'#help' => elgg_echo('installation:registration:description'),
	'name' => 'allow_registration',
	'checked' => (bool) elgg_get_config('allow_registration'),
	'switch' => true,
]);

$result .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('config:users:user_joined_river'),
	'name' => 'user_joined_river',
	'checked' => (bool) elgg_get_config('user_joined_river'),
	'switch' => true,
]);

$result .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('installation:adminvalidation:label'),
	'#help' => elgg_echo('installation:adminvalidation:description'),
	'name' => 'require_admin_validation',
	'checked' => (bool) elgg_get_config('require_admin_validation'),
	'switch' => true,
]);

$classes = ['elgg-divide-left', 'plm', 'elgg-admin-users-admin-validation-notification'];
if (!(bool) elgg_get_config('require_admin_validation')) {
	$classes[] = 'hidden';
}

$result .= elgg_view_field([
	'#type' => 'switch',
	'#label' => elgg_echo('installation:adminvalidation:notification:label'),
	'#help' => elgg_echo('installation:adminvalidation:notification:description'),
	'#class' => $classes,
	'name' => 'admin_validation_notification',
	'value' => elgg_get_config('admin_validation_notification'),
]);

$result .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('config:users:remove_unvalidated_users_days'),
	'#help' => elgg_echo('config:users:remove_unvalidated_users_days:help'),
	'name' => 'remove_unvalidated_users_days',
	'value' => elgg_get_config('remove_unvalidated_users_days'),
	'min' => 0,
]);

$result .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('config:users:can_change_username'),
	'#help' => elgg_echo('config:users:can_change_username:help'),
	'name' => 'can_change_username',
	'checked' => (bool) elgg_get_config('can_change_username'),
	'switch' => true,
]);

echo elgg_view_module('info', elgg_echo('admin:settings:users'), $result);
