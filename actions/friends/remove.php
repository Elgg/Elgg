<?php

	/**
	 * Elgg remove friend action
	 * 
	 * @package Elgg
	 * @subpackage Core

	 * @author Curverider Ltd

	 * @link http://elgg.org/
	 */

	// Ensure we are logged in
		gatekeeper();
		action_gatekeeper();
		
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