<?php


$title = elgg_view_title(elgg_echo('diagnostics'));

$diagnostics = "<h3>".elgg_echo('diagnostics:report')."</h3>";
$diagnostics .= elgg_echo('diagnostics:description');
$diagnostics .= elgg_view('diagnostics/forms/download');

// unit tests
$unit_tests = "<h3>".elgg_echo('diagnostics:unittester')."</h3>";
$unit_tests .= "<p>" . elgg_echo('diagnostics:unittester:description') . "</p>";
$unit_tests .= "<p>" . elgg_echo('diagnostics:unittester:warning') . "</p>";

if (isset($CONFIG->debug)) {
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
$title
<div class="admin_settings diagnostics">
	$diagnostics
	$unit_tests
</div>
HTML;
