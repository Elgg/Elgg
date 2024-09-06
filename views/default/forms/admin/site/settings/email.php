<?php
/**
 * Advanced site settings, email section.
 */

$body = elgg_view_field([
	'#type' => 'switch',
	'#label' => elgg_echo('config:email_html_part:label'),
	'#help' => elgg_echo('config:email_html_part:help'),
	'name' => 'email_html_part',
	'value' => elgg_get_config('email_html_part'),
]);

$body .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('config:email_html_part_images:label'),
	'#help' => elgg_echo('config:email_html_part_images:help'),
	'name' => 'email_html_part_images',
	'value' => elgg_get_config('email_html_part_images'),
	'options_values' => [
		'no' => elgg_echo('option:no'),
		'base64' => elgg_echo('config:email_html_part_images:base64'),
		'attach' => elgg_echo('config:email_html_part_images:attach'),
	],
]);

$body .= elgg_view_field([
	'#type' => 'switch',
	'#label' => elgg_echo('config:delayed_email:label'),
	'#help' => elgg_echo('config:delayed_email:help'),
	'name' => 'enable_delayed_email',
	'value' => elgg_get_config('enable_delayed_email'),
]);

echo elgg_view_module('info', elgg_echo('config:email'), $body);
