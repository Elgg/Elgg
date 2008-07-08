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
	$user_name = $page_owner_user->username;

	if($page_owner) {
		//if the user is looking at their own stuff, don't display the name
		if($_SESSION['user']->getGUID() == $page_owner_user->guid){
			$info = "<h2>Your " . $vars['title'] . "</h2>";
		}else{
			$info = "<h2>" . $user_name . "'s " . $vars['title'] . "</h2>";
		}
		$icon = elgg_view("profile/icon",array('entity' => $page_owner_user, 'size' => 'small'));
		echo elgg_view_listing($icon, $info);
	} else {
		$info = "<h2>" . $vars['title'] . "</h2>"; //"<h2 class=\"page_title\">"
		echo elgg_view_listing($icon, $info);
	}


?>