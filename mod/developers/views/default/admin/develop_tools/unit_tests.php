<?php
/**
 * Elgg unit and integration tests
 *
 */

echo '<p>' . elgg_echo('developers:unit_tests:description') . '</p>';
echo '<p><strong>' . elgg_echo('developers:unit_tests:warning') . '</strong></p>';

$elgg_engine = sanitise_filepath(elgg_get_engine_path());
$root_engine = sanitise_filepath(elgg_get_root_path() . 'engine');
if ($elgg_engine === $root_engine) {
	$href = 'engine/tests/suite.php';
} else {
	$href = 'vendor/elgg/elgg/engine/tests/suite.php';
	
	echo '<p><strong>' . elgg_echo('developers:unit_tests:warning:dependencies') . '</strong></p>';
}

// create a button to run tests
$params = array(
	'text' => elgg_echo('developers:unit_tests:run'),
	'href' => $href,
	'class' => 'elgg-button elgg-button-submit',
	'is_trusted' => true,
);
echo '<p>' . elgg_view('output/url', $params) . '</p>';
