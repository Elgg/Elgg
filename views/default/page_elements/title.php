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
	$page_owner_user = get_entity($page_owner);

	$submenu = get_submenu(); // elgg_view('canvas_header/submenu');
	if (!empty($submenu)) $submenu = "<ul>" . $submenu . "</ul>";
	
	if($page_owner && $page_owner_user->guid != $_SESSION['user']->getGUID()) {
		$info = "<h2>" . $vars['title'] . "</h2>";
		$icon = elgg_view("profile/icon",array('entity' => $page_owner_user, 'size' => 'tiny'));
		$display = "<div id=\"canvas_header\">";
		$display .= "<div id=\"canvas_header_icon\">" . $icon . "</div>";
		$display .= "<div id=\"canvas_header_content\">" . $info . "</div>";
		if (!empty($submenu) && $vars['submenu'] == true)
			$display .= "<div id=\"canvas_header_submenu\">" . $submenu . "</div>"; // plugins can extend this to add menu options
		$display .= "</div>";
	} else {
		$info = "<h2>" . $vars['title'] . "</h2>";
		$display = "<div id=\"canvas_header\">";
		$display .= "<div id=\"canvas_header_content\">" . $info . "</div>";
		if (!empty($submenu)  && $vars['submenu'] == true)
			$display .= "<div id=\"canvas_header_submenu\">" . $submenu . "</div>"; // plugins can extend this to add menu options
		$display .= "</div>";
	}


	//print to screen
		echo $display;



?>