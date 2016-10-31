<?php
/**
 * Advanced site settings, content access section.
 */

$body = elgg_view_field([
	'#type' => 'access',
	'options_values' => [
		ACCESS_PRIVATE => elgg_echo('PRIVATE'),
		ACCESS_FRIENDS => elgg_echo('access:friends:label'),
		ACCESS_LOGGED_IN => elgg_echo('LOGGED_IN'),
		ACCESS_PUBLIC => elgg_echo('PUBLIC'),
	],
	'name' => 'default_access',
	'#label' => elgg_echo('installation:sitepermissions'),
	'#help' => elgg_echo('admin:site:access:warning'),
	'value' => elgg_get_config('default_access'),
]);

$body .= elgg_view_field([
	'#type' => 'checkbox',
	'label' => elgg_echo('installation:allow_user_default_access:label'),
	'#help' => elgg_echo('installation:allow_user_default_access:description'),
	'name' => 'allow_user_default_access',
	'checked' => (bool)elgg_get_config('allow_user_default_access'),
]);

echo elgg_view_module('inline', elgg_echo('admin:legend:content_access'), $body, ['id' => 'elgg-settings-advanced-content-access']);
