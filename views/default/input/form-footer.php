<?php
/**
 * Render and wrap a form footer
 *
 * @uses $vars['action']    Action name
 * @uses $vars['body_vars'] Form body vars passed to elgg_view_form()
 */
$action = elgg_extract('action', $vars);
if (!$action) {
	elgg_log('The "action" view parameter is required', 'ERROR');
	return;
}

$body_vars = elgg_extract('body_vars', $vars);

$footer = elgg_view("forms/$action-footer", $body_vars);
if (!$footer) {
	return;
}

$class[] = 'elgg-form-footer';
$class[] = 'elgg-form-footer-' . preg_replace('/[^a-z0-9]/i', '-', $action);

echo elgg_format_element('div', [
	'class' => $class,
], $footer);
