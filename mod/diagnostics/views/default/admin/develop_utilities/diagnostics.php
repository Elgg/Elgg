<?php
/**
 * Diagnostics admin page
 */

$diagnostics_title = elgg_echo('diagnostics:report');
$diagnostics = '<p>' . elgg_echo('diagnostics:description') .'</p>';
$params = array(
	'text' => elgg_echo('diagnostics:download'),
	'href' => 'action/diagnostics/download',
	'class' => 'elgg-button elgg-button-submit',
	'is_action' => true,
	'is_trusted' => true,
);
$diagnostics .= '<p>' . elgg_view('output/url', $params) . '</p>';

// unit tests
$unit_tests_title = elgg_echo('diagnostics:unittester');
$unit_tests .= '<p>' . elgg_echo('diagnostics:unittester:description') . '</p>';
$unit_tests .= '<p>' . elgg_echo('diagnostics:unittester:warning') . '</p>';

if (elgg_get_config('debug')) {
	// create a button to run tests
	$params = array(
		'text' => elgg_echo('diagnostics:test:executeall'),
		'href' => 'engine/tests/suite.php',
		'class' => 'elgg-button elgg-button-submit',
		'is_trusted' => true,
	);
	$unit_tests .= '<p>' . elgg_view('output/url', $params) . '</p>';
} else {
	// no tests when not in debug mode
	$unit_tests .= elgg_echo('diagnostics:unittester:debug');
}

// display admin body
echo elgg_view_module('inline', $diagnostics_title, $diagnostics, array('class' => 'elgg-form-settings'));
echo elgg_view_module('inline', $unit_tests_title, $unit_tests, array('class' => 'elgg-form-settings'));
