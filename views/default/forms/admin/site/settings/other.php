<?php
/**
 * Advanced site settings, other section.
 */

$body = elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('config:remove_branding:label'),
	'#help' => elgg_echo('config:remove_branding:help'),
	'name' => 'remove_branding',
	'checked' => (bool) elgg_get_config('remove_branding'),
	'switch' => true,
]);

$body .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('config:disable_rss:label'),
	'#help' => elgg_echo('config:disable_rss:help'),
	'name' => 'disable_rss',
	'checked' => (bool) elgg_get_config('disable_rss'),
	'switch' => true,
]);

$body .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('config:friendly_time_number_of_days:label'),
	'#help' => elgg_echo('config:friendly_time_number_of_days:help'),
	'name' => 'friendly_time_number_of_days',
	'value' => (int) elgg_get_config('friendly_time_number_of_days', 30),
	'min' => 0,
]);

echo elgg_view_module('info', elgg_echo('other'), $body, ['id' => 'elgg-settings-advanced-other']);
