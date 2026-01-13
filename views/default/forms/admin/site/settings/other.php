<?php
/**
 * Advanced site settings, other section.
 */

$body = elgg_view_field([
	'#type' => 'switch',
	'#label' => elgg_echo('config:color_schemes_enabled:label'),
	'#help' => elgg_echo('config:color_schemes_enabled:help'),
	'name' => 'color_schemes_enabled',
	'value' => elgg_get_config('color_schemes_enabled'),
]);

$body .= elgg_view_field([
	'#type' => 'switch',
	'#label' => elgg_echo('config:remove_branding:label'),
	'#help' => elgg_echo('config:remove_branding:help'),
	'name' => 'remove_branding',
	'value' => elgg_get_config('remove_branding'),
]);

$body .= elgg_view_field([
	'#type' => 'switch',
	'#label' => elgg_echo('config:disable_rss:label'),
	'#help' => elgg_echo('config:disable_rss:help'),
	'name' => 'disable_rss',
	'value' => elgg_get_config('disable_rss'),
]);

$body .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('config:friendly_time_number_of_days:label'),
	'#help' => elgg_echo('config:friendly_time_number_of_days:help'),
	'name' => 'friendly_time_number_of_days',
	'value' => (int) elgg_get_config('friendly_time_number_of_days'),
	'min' => 0,
]);

$body .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('config:message_delay:label'),
	'#help' => elgg_echo('config:message_delay:help'),
	'name' => 'message_delay',
	'value' => (int) elgg_get_config('message_delay'),
	'min' => 1,
]);

echo elgg_view_module('info', elgg_echo('other'), $body, ['id' => 'elgg-settings-advanced-other']);
