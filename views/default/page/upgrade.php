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

$url = elgg_get_simplecache_url('css', 'admin');
elgg_register_css('elgg.admin', $url);
elgg_load_css('elgg.admin');

$refresh_url = htmlspecialchars($refresh_url);

$upgrades = elgg_extract('upgrades', $vars);

$list = elgg_view('admin/upgrades/list', $vars);

// render content before head so that JavaScript and CSS can be loaded. See #4032
$body = "<div style='margin-top:200px; width: 600px; margin: auto;'>$list</div>";

$foot = elgg_view('page/elements/foot');

$head = elgg_view('page/elements/head', $vars['head']);

echo elgg_view("page/elements/html", array("head" => $head, "body" => $body . $foot));
