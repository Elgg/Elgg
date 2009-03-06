<?php

	/**
	 * Elgg profile index
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	// Get the Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	// Get the username
		$username = get_input('username');
		
		$body = "";
		
	// Try and get the user from the username and set the page body accordingly
		if ($user = get_user_by_username($username)) {
			
			if ($user->isBanned() && !isadminloggedin()) {
				forward(); exit;
			}
			$body = elgg_view_entity($user,true);
			$title = $user->name;

			$body = elgg_view_layout('widgets',$body);
			
		} else {
			
			$body = elgg_echo("profile:notfound");
			$title = elgg_echo("profile");
			
		}

		page_draw($title, $body);
		
?>