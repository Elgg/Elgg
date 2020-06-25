<?php
/**
 * Plugin settings for the WebServices plugin
 *
 * @uses $vars['entity'] the plugin entity
 */

/* @var $plugin ElggPlugin */
$plugin = elgg_extract('entity', $vars);

$authentication = elgg_view('output/longtext', [
	'value' => elgg_echo('web_services:settings:authentication:description'),
]);

$authentication .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('web_services:settings:authentication:allow_key'),
	'#help' => elgg_echo('web_services:settings:authentication:allow_key:help'),
	'name' => 'params[auth_allow_key]',
	'value' => 1,
	'checked' => (bool) $plugin->getSetting('auth_allow_key', 1),
]);

$authentication .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('web_services:settings:authentication:allow_hmac'),
	'#help' => elgg_echo('web_services:settings:authentication:allow_hmac:help'),
	'name' => 'params[auth_allow_hmac]',
	'value' => 1,
	'checked' => (bool) $plugin->getSetting('auth_allow_hmac', 1),
]);

echo elgg_view_module('inline', elgg_echo('web_services:settings:authentication'), $authentication);
