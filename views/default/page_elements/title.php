<?php
/**
 * Elgg title element
 * @uses $vars['title'] The page title
 */

$page_owner = page_owner();
$page_owner_entity = get_entity($page_owner);

$display = "<h2>" . $vars['title'] . "</h2>";

$submenu = get_submenu();
if (!empty($submenu)) {
	$submenu = "<ul class='submenu extended'>" . $submenu . "</ul>";
}

if (!empty($submenu)  && $vars['submenu'] == true) {
	// plugins can extend this to add menu options
	$display .= $submenu;
}

echo $display;