<?php
/**
 * Elgg diagnostics
 *
 * @package ElggDiagnostics
 */

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

admin_gatekeeper();
elgg_set_context('admin');

// system diagnostics
$content = elgg_view_title(elgg_echo('diagnostics'));
$content .= "<div class='admin_settings diagnostics'>";
$content .= elgg_view('page_elements/content', array('body' =>
	"<h3>".elgg_echo('diagnostics:report')."</h3>".elgg_echo('diagnostics:description') . elgg_view('diagnostics/forms/download'))
);

// unit tests
$content .= "<h3>".elgg_echo('diagnostics:unittester')."</h3>";
$test_body = "<p>" . elgg_echo('diagnostics:unittester:description') . "</p>";
$test_body .= "<p>" . elgg_echo('diagnostics:unittester:warning') . "</p>";

if (isset($CONFIG->debug)) {
	// create a button to run tests
	$js = "onclick=\"window.location='".elgg_get_site_url()."engine/tests/suite.php'\"";
	$params = array('value' => elgg_echo('diagnostics:test:executeall'), 'js' => $js);
	$test_body .= elgg_view('input/button', $params);
} else {
	// no tests when not in debug mode
	$test_body .= elgg_echo('diagnostics:unittester:debug');
}

$content .= elgg_view('page_elements/content', array(
	'body' => $test_body)
);
$content .= "</div>";

$body = elgg_view_layout("one_column_with_sidebar", array('content' => $content));
echo elgg_view_page(elgg_echo('diagnostics'), $body);
