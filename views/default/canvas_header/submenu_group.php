<?php
/**
 * Elgg default layout
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */
if (isset($vars['group_name'])) {
	$groupname = $vars['group_name'];
} else {
	$groupname = "main";
}

if (isset($vars['submenu'])) {
	$submenu = "<ul class='submenu {$groupname}'>" . $vars['submenu'] . "</ul>";
	echo $submenu;
}