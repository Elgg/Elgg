<?php
/**
 * Form body for configuring maintenance mode
 */

$site = elgg_get_site_entity();

echo elgg_view('output/longtext', [
	'value' => elgg_echo('admin:maintenance_mode:instructions'),
]);

echo elgg_view_field([
	'#type' => 'switch',
	'#label' => elgg_echo('admin:maintenance_mode:mode_label'),
	'name' => 'mode',
	'value' => elgg_get_config('elgg_maintenance_mode'),
]);

echo elgg_view_field([
	'#type' => 'longtext',
	'#label' => elgg_echo('admin:maintenance_mode:message_label'),
	'name' => 'message',
	'id' => 'message',
	'value' => $site->elgg_maintenance_message ?: elgg_echo('admin:maintenance_mode:default_message'),
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'text' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
