<?php
/**
 * Elgg diagnostics
 * 
 * @package ElggDiagnostics
 * @author Curverider Ltd
 * @link http://elgg.com/
 */

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

admin_gatekeeper();
set_context('admin');

// Set admin user for user block
set_page_owner($_SESSION['guid']);

// system diagnostics
$body = elgg_view_title(elgg_echo('diagnostics'));
$body .= elgg_view('page_elements/contentwrapper', array('body' => 
	elgg_echo('diagnostics:description') . elgg_view('diagnostics/forms/download'))
);

// unit tests
$body .= elgg_view_title(elgg_echo('diagnostics:unittester'));
if (isset($CONFIG->debug)) {
	// create a button to run tests
	$js = "onclick=\"window.location='{$CONFIG->wwwroot}engine/tests/suite.php'\"";
	$params = array('type' => 'button', 'value' => elgg_echo('diagnostics:test:executeall'), 'js' => $js);
	$button = elgg_view('input/button', $params);
} else {
	// no tests when not in debug mode
	$button = elgg_echo('diagnostics:unittester:debug');
}
$body .= elgg_view('page_elements/contentwrapper', array('body' => 
	elgg_echo('diagnostics:unittester:description') . "<br />$button")
);

// create page
page_draw(elgg_echo('diagnostics'), elgg_view_layout("two_column_left_sidebar", '', $body));
