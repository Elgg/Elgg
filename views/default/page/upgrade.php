<?php
/**
 * Page shell for upgrade script
 *
 * Displays an ajax loader until upgrade is complete
 *
 * @uses $vars['head']        Parameters for the <head> element
 * @uses $vars['body']        The main content of the page
 * @uses $vars['sysmessages'] A 2d array of various message registers, passed from system_messages()
 * @uses $var['forward']      A relative path to forward to after upgrade. Defaults to /admin
 */

$refresh_url = elgg_http_add_url_query_elements(elgg_get_site_url() . 'upgrade.php', array(
	'upgrade' => 'upgrade',
	'forward' => elgg_extract('forward', $vars, '/admin')
));

$refresh_url = htmlspecialchars($refresh_url);
// render content before head so that JavaScript and CSS can be loaded. See #4032
$body = "<div style='margin-top:200px'>" . elgg_view('graphics/ajax_loader', array('hidden' => false)) . "</div>";

$head = elgg_view('page/elements/head', $vars['head']);
$head .= "<meta http-equiv='refresh' content='1;url=$refresh_url' />";

echo elgg_view("page/elements/html", array("head" => $head, "body" => $body));
