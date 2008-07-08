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

	if($page_owner) {
		$info = "<h2>" . $vars['title'] . "</h2>";
		$icon = elgg_view("profile/icon",array('entity' => $page_owner_user, 'size' => 'small'));
		echo elgg_view_listing($icon, $info);
	} else {
		$info = "<h2>" . $vars['title'] . "</h2>"; //"<h2 class=\"page_title\">"
		echo elgg_view_listing($icon, $info);
	}


?>