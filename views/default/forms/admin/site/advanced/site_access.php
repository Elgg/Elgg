<?php
/**
 * Advanced site settings, site access section.
 */

// new user registration
$body = elgg_view_field([
	'#type' => 'checkbox',
	'label' => elgg_echo('installation:registration:label'),
	'#help' => elgg_echo('installation:registration:description'),
	'name' => 'allow_registration',
	'checked' => (bool)elgg_get_config('allow_registration'),
]);

// walled garden
$body .= elgg_view_field([
	'#type' => 'checkbox',
	'label' => elgg_echo('installation:walled_garden:label'),
	'#help' => elgg_echo('installation:walled_garden:description'),
	'name' => 'walled_garden',
	'checked' => (bool)elgg_get_config('walled_garden'),
]);

echo elgg_view_module('inline', elgg_echo('admin:legend:site_access'), $body, ['id' => 'elgg-settings-advanced-site-access']);
