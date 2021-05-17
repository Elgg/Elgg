<?php
/**
 * Form body for configuring maintenance mode
 */

$site = elgg_get_site_entity();

echo elgg_view('output/longtext', [
	'value' => elgg_echo('admin:maintenance_mode:instructions'),
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('admin:maintenance_mode:mode_label'),
	'name' => 'mode',
	'checked' => (bool) elgg_get_config('elgg_maintenance_mode'),
	'switch' => true,
	'value' => 1,
	'default' => 0,
]);

echo elgg_view_field([
	'#type' => 'longtext',
	'#label' => elgg_echo('admin:maintenance_mode:message_label'),
	'name' => 'message',
	'id' => 'message',
	'value' => $site->getPrivateSetting('elgg_maintenance_message') ?: elgg_echo('admin:maintenance_mode:default_message'),
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
