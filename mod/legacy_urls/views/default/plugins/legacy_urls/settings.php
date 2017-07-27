<?php
/**
 * Select how to redirect to the new URL
 *
 * Options:
 *  * immediate 301
 *  * immediate 301 with error message
 *  * landing page with redirect in 5 seconds
 */

$plugin = elgg_extract('entity', $vars);

$redirect_method = $plugin->redirect_method ?: 'immediate';

echo elgg_view_field([
	'#type' => 'radio',
	'#label' => elgg_echo('legacy_urls:instructions'),
	'name' => 'params[redirect_method]',
	'value' => $redirect_method,
	'options' => [
		elgg_echo('legacy_urls:immediate') => 'immediate',
		elgg_echo('legacy_urls:immediate_error') => 'immediate_error',
		elgg_echo('legacy_urls:landing') => 'landing',
	]
]);
