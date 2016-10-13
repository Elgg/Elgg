<?php
/**
 * Form body for setting robots.txt
 */

$site = elgg_get_site_entity();

echo elgg_view_field([
	'#type' => 'plaintext',
	'#label' => elgg_echo('admin:robots.txt:instructions'),
	'name' => 'text',
	'value' => $site->getPrivateSetting('robots.txt'),
]);

echo elgg_view_field([
	'#type' => 'plaintext',
	'#label' => elgg_echo('admin:robots.txt:plugins'),
	'value' => elgg_trigger_plugin_hook('robots.txt', 'site', ['site' => $site], ''),
	'readonly' => true,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
