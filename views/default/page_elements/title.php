<?php

	/**
	 * Elgg title element
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['title'] The page title
	 */

	$page_owner = page_owner();
	$page_owner_user = get_user($page_owner);

	if($page_owner && $page_owner_user->guid != $_SESSION['user']->getGUID()) {
		$info = "<h2>" . $vars['title'] . "</h2>";
		$icon = elgg_view("profile/icon",array('entity' => $page_owner_user, 'size' => 'tiny'));
		$display = "<div id=\"canvas_header\">";
		$display .= "<div id=\"canvas_header_icon\">" . $icon . "</div>";
		$display .= "<div id=\"canvas_header_content\">" . $info . "</div>";
		$display .= "<div id=\"canvas_header_submenu\">" . elgg_view("canvas_header/submenu") . "</div>"; // plugins can extend this to add menu options
		$display .= "</div>";
	} else {
		$info = "<h2>" . $vars['title'] . "</h2>";
		$display = "<div id=\"canvas_header\">";
		$display .= "<div id=\"canvas_header_content\">" . $info . "</div>";
		$display .= "<div id=\"canvas_header_submenu\">" . elgg_view("canvas_header/submenu") . "</div>"; // plugins can extend this to add menu options
		$display .= "</div>";
	}


	//print to screen
		echo $display;



?>