<?php
/**
 * Elgg title element
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['title'] The page title
 */

$page_owner = page_owner();
$page_owner_entity = get_entity($page_owner);

$info = "<h2>" . $vars['title'] . "</h2>";
if ($page_owner_entity instanceOf ElggGroup) {
	$display = "<div id=\"content_area_group_title\">" . $info . "</div>";
} else {
	$display = "<div id=\"content_area_user_title\">" . $info . "</div>";
}

$submenu = get_submenu();
if (!empty($submenu)) {
	$submenu = "<ul>" . $submenu . "</ul>";
}
if (!empty($submenu) && $vars['submenu'] == true) {
	// plugins can extend this to add menu options
	$display .= "<div id=\"owner_block_submenu\">" . $submenu . "</div>";
}

echo $display;
