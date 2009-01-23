<?php

	/**
	 * Elgg remove friend action
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	// Ensure we are logged in
		gatekeeper();
		
	// Get the GUID of the user to friend
		$friend_guid = get_input('friend');
		$friend = get_entity($friend_guid);
		$errors = false;

	// Get the user
		try{
			if ($friend instanceof ElggUser)
				$_SESSION['user']->removeFriend($friend_guid);
			else
			{
				register_error(sprintf(elgg_echo("friends:remove:failure"),$friend->name));
				$errors = true;
			}
		} catch (Exception $e) {
			register_error(sprintf(elgg_echo("friends:remove:failure"),$friend->name));
			$errors = true;
		}
		if (!$errors)
			system_message(sprintf(elgg_echo("friends:remove:successful"),$friend->name));			
		
	// Forward to the user friends page
		forward("pg/friends/" . $_SESSION['user']->username . "/");
		
?>