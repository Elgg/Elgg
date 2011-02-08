<?php


$diagnostics_title = elgg_echo('diagnostics:report');
$diagnostics = elgg_echo('diagnostics:description');
$diagnostics .= elgg_view('diagnostics/forms/download');

// unit tests
$unit_tests_title = elgg_echo('diagnostics:unittester');
$unit_tests .= "<p>" . elgg_echo('diagnostics:unittester:description') . "</p>";
$unit_tests .= "<p>" . elgg_echo('diagnostics:unittester:warning') . "</p>";

if (elgg_get_config('debug')) {
	// create a button to run tests
	$params = array(
		'text' => elgg_echo('diagnostics:test:executeall'),
		'href' => elgg_get_site_url() . 'engine/tests/suite.php',
		'class' => 'elgg-submit-button',
	);
	$unit_tests .= elgg_view('output/url', $params);
} else {
	// no tests when not in debug mode
	$unit_tests .= elgg_echo('diagnostics:unittester:debug');
}

// display admin body
echo <<<HTML
<div class="elgg-module elgg-module-inline">
	<div class="elgg-head">
		<h3>$diagnostics_title</h3>
	</div>
	<div class="elgg-body">
		$diagnostics
	</div>
</div>
<div class="elgg-module elgg-module-inline">
	<div class="elgg-head">
		<h3>$unit_tests_title</h3>
	</div>
	<div class="elgg-body">
		$unit_tests
	</div>
</div>
HTML;
