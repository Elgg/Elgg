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

echo elgg_view_module('inline', elgg_echo('other'), $body, ['id' => 'elgg-settings-advanced-other']);
