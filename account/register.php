<?php

	/**
	 * Elgg registration page
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	/**
	 * Start the Elgg engine
	 */
		require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

		$friend_guid = (int) get_input('friend_guid',0);
		$invitecode = get_input('invitecode');
		
	// If we're not logged in, display the registration page
		if (!isloggedin()) {
			page_draw(elgg_echo('register'), elgg_view("account/forms/register", array('friend_guid' => $friend_guid, 'invitecode' => $invitecode)));
	// Otherwise, forward to the index page
		} else {
			forward();
		}
		
?>