<?php

	/**
	 * Elgg profile editor
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	// Get the Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");	

	// If we're not logged on, forward the user elsewhere
		if (!isloggedin()) forward();
		
	// Get current user for now
		$user = $_SESSION['user'];
		
	// Get form, if we're allowed to edit
		if ($user->canEdit()) {
			
			$body = elgg_view("profile/edit",array('entity' => $user));
			 
		} else {
			
			$body = elgg_echo("profile:noaccess");
			
		}
		
	// Draw the page
		page_draw(elgg_echo("profile:edit"),$body);

?>