<?php
/**
 * Select how to redirect to the new URL
 *
 * Options:
 *  * immediate 301
 *  * immediate 301 with error message
 *  * landing page with redirect in 5 seconds
 */

// set default value
if (!isset($vars['entity']->redirect_method)) {
	$vars['entity']->redirect_method = 'immediate';
}

$method_label = elgg_echo('legacy_urls:instructions');

$method_input = elgg_view('input/radio', array(
	'name' => 'params[redirect_method]',
	'value' => $vars['entity']->redirect_method,
	'options' => array(
		elgg_echo('legacy_urls:immediate') => 'immediate',
		elgg_echo('legacy_urls:immediate_error') => 'immediate_error',
		elgg_echo('legacy_urls:landing') => 'landing',
	)
));

echo <<<HTML
<div>
	<label>$method_label</label>
	$method_input
</div>
HTML;
