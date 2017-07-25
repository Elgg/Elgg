<?php
/**
 * Form body for setting robots.txt
 */

$site = elgg_get_site_entity();
$mode = (int) elgg_get_config('elgg_maintenance_mode', null);

$message = $site->getPrivateSetting('elgg_maintenance_message');
if (!$message) {
	$message = elgg_echo('admin:maintenance_mode:default_message');
}

echo elgg_view('output/longtext', [
	'value' => elgg_echo('admin:maintenance_mode:instructions'),
]);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('admin:maintenance_mode:mode_label'),
	'name' => 'mode',
	'options_values' => [
		'1' => elgg_echo('on'),
		'0' => elgg_echo('off'),
	],
	'value' => $mode,
]);

echo elgg_view_field([
	'#type' => 'longtext',
	'#label' => elgg_echo('admin:maintenance_mode:message_label'),
	'name' => 'message',
	'id' => 'message',
	'value' => $message,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
