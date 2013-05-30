<?php
/**
 * Page shell for upgrade script
 *
 * Displays an ajax loader until upgrade is complete
 */

// render content before head so that JavaScript and CSS can be loaded. See #4032
$body = elgg_view('page/elements/body/upgrade', $vars);

$head = elgg_view('page/elements/head', $vars);
$head .= "<meta http-equiv='refresh' content='1;url=" . elgg_get_site_url() . "upgrade.php?upgrade=upgrade'/>";

echo elgg_view("page/shell", array("head" => $head, "body" => $body));
