<?php
/**
 * Diagnostics admin page
 */

$diagnostics_title = elgg_echo('diagnostics:report');
$diagnostics = '<p>' . elgg_echo('diagnostics:description') .'</p>';
$params = [
	'text' => elgg_echo('download'),
	'href' => 'action/diagnostics/download',
	'class' => 'elgg-button elgg-button-submit',
	'is_action' => true,
	'is_trusted' => true,
];
$diagnostics .= '<p>' . elgg_view('output/url', $params) . '</p>';

echo elgg_view_module('inline', $diagnostics_title, $diagnostics, ['class' => 'elgg-form-settings']);
