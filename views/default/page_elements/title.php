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

	if($_SESSION['user']->getGUID() != $page_owner_user->guid){
		$user_name = $page_owner_user->username . "'s";
	} else {
 		$user_name = elgg_echo('Your');
	}

	if($page_owner) {
		$icon = elgg_view("profile/icon",array('entity' => $page_owner_user, 'size' => 'small'));
		$info = $user_name . " " . $vars['title'];
		echo elgg_view_listing($icon, $info);
	} else {
		echo "<h2 class=\"page_title\">" . $vars['title'] . "</h2>";
	}

?>