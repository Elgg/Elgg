<?php

	/**
	 * Elgg add friend action
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
		try {
			if (!$_SESSION['user']->addFriend($friend_guid)) $errors = true;
		} catch (Exception $e) {
			register_error(sprintf(elgg_echo("friends:add:failure"),$friend->name));
			$errors = true;
		}
		if (!$errors){
			// add to river
	        add_to_river('friends/river/create','friend',$_SESSION['user']->guid,$friend_guid);
			system_message(sprintf(elgg_echo("friends:add:successful"),$friend->name));
		}
		
	// Forward to the user friends page
		forward("pg/friends/" . $_SESSION['user']->username . "/");
		
?>