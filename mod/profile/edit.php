<?php

	/**
	 * Elgg profile editor
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	// Get the Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");	

	// If we're not logged on, forward the user elsewhere
		if (!isloggedin()) forward();
		
	// Get current user for now
		if ($user = page_owner()) {
			$user = page_owner_entity();			
		} else {
			$user = $_SESSION['user'];
			if (!$user) $user = get_entity($_SESSION['id']);
			set_page_owner($user->getGUID());
		}
		
	// Get form, if we're allowed to edit
		if ($user->canEdit()) {
			
    		$area2 = elgg_view_title(elgg_echo('profile:edit'));
			$area2 .= elgg_view("profile/edit",array('entity' => $user));
			 
		} else {
			
			$area2 = elgg_echo("profile:noaccess");
			
		}
		
		$area1 = "";
		
    // get the required canvas area
        $body = elgg_view_layout("two_column_left_sidebar", $area1, $area2);
		
	// Draw the page
		page_draw(elgg_echo("profile:edit"),$body);

?>