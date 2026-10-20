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
	'#type' => 'switch',
	'#label' => elgg_echo('web_services:settings:authentication:allow_key'),
	'#help' => elgg_echo('web_services:settings:authentication:allow_key:help'),
	'name' => 'params[auth_allow_key]',
	'value' => $plugin->auth_allow_key,
]);

$authentication .= elgg_view_field([
	'#type' => 'switch',
	'#label' => elgg_echo('web_services:settings:authentication:allow_hmac'),
	'#help' => elgg_echo('web_services:settings:authentication:allow_hmac:help'),
	'name' => 'params[auth_allow_hmac]',
	'value' => $plugin->auth_allow_hmac,
]);

echo elgg_view_module('info', elgg_echo('web_services:settings:authentication'), $authentication);
