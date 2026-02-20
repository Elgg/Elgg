<?php

$body = elgg_view_field([
	'#type' => 'switch',
	'label' => elgg_echo('installation:walled_garden:label'),
	'#help' => elgg_echo('installation:walled_garden:description'),
	'name' => 'walled_garden',
	'value' => elgg_get_config('walled_garden'),
]);

$body .= elgg_view_field([
	'#type' => 'access',
	'options_values' => [
		ACCESS_PRIVATE => elgg_echo('access:label:private'),
		ACCESS_LOGGED_IN => elgg_echo('access:label:logged_in'),
		ACCESS_PUBLIC => elgg_echo('access:label:public'),
	],
	'name' => 'default_access',
	'#label' => elgg_echo('installation:sitepermissions'),
	'#help' => elgg_echo('admin:site:access:warning'),
	'value' => elgg_get_config('default_access'),
]);

$body .= elgg_view_field([
	'#type' => 'switch',
	'label' => elgg_echo('installation:allow_user_default_access:label'),
	'#help' => elgg_echo('installation:allow_user_default_access:description'),
	'name' => 'allow_user_default_access',
	'value' => elgg_get_config('allow_user_default_access'),
]);

$body .= elgg_view_field([
	'#type' => 'number',
	'name' => 'default_limit',
	'#label' => elgg_echo('installation:default_limit'),
	'value' => elgg_get_config('default_limit'),
	'min' => 1,
	'step' => 1,
]);

$body .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('config:content:pagination_behaviour'),
	'#help' => elgg_echo('config:content:pagination_behaviour:help'),
	'name' => 'pagination_behaviour',
	'value' => elgg_get_config('pagination_behaviour'),
	'options_values' => [
		'navigate' => elgg_echo('config:content:pagination_behaviour:navigate'),
		'ajax-replace' => elgg_echo('config:content:pagination_behaviour:ajax-replace'),
		'ajax-append' => elgg_echo('config:content:pagination_behaviour:ajax-append'),
		'ajax-append-auto' => elgg_echo('config:content:pagination_behaviour:ajax-append-auto'),
	],
]);

$body .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('config:content:mentions_display_format'),
	'#help' => elgg_echo('config:content:mentions_display_format:help'),
	'name' => 'mentions_display_format',
	'value' => elgg_get_config('mentions_display_format'),
	'options_values' => [
		'display_name' => elgg_echo('config:content:mentions_display_format:display_name'),
		'username' => elgg_echo('config:content:mentions_display_format:username'),
	],
]);

$body .= elgg_view_field([
	'#type' => 'switch',
	'#label' => elgg_echo('config:content:trash_enabled:label'),
	'#help' => elgg_echo('config:content:trash_enabled:help'),
	'name' => 'trash_enabled',
	'value' => elgg_get_config('trash_enabled'),
]);

$body .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('config:content:trash_retention:label'),
	'#help' => elgg_echo('config:content:trash_retention:help'),
	'#class' => ['elgg-divide-left', 'plm'],
	'name' => 'trash_retention',
	'value' => (int) elgg_get_config('trash_retention'),
	'min' => 0,
]);

echo elgg_view_module('info', elgg_echo('admin:legend:content'), $body);
