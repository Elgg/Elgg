<?php
/**
 * Maintenance mode layout
 *
 * @uses $vars['message'] Maintenance message
 * @uses $vars['site']    Site entity
 */

$body = '<h1>' . $vars['site']->name . '</h1>';
$body .= elgg_view('output/longtext', array('value' => $vars['message']));
$body .= elgg_view('core/maintenance/login');

echo elgg_view_module('maintenance', '', $body, array(
	'header' => ' ',
	'footer' => ' ',
));
