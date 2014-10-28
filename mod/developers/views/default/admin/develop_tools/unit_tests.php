<?php
/**
 * Elgg unit and integration tests
 *
 */

echo '<p>' . elgg_echo('developers:unit_tests:description') . '</p>';
echo '<p><strong>' . elgg_echo('developers:unit_tests:warning') . '</strong></p>';

// create a button to run tests
$params = array(
	'text' => elgg_echo('developers:unit_tests:run'),
	'href' => 'engine/tests/suite.php',
	'class' => 'elgg-button elgg-button-submit',
	'is_trusted' => true,
);
echo '<p>' . elgg_view('output/url', $params) . '</p>';
