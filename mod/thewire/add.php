<?php

	/**
	 * Elgg thewire add entry page
	 * 
	 * @package ElggTheWire
	 *
	 */

	// Load Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
	// If we're not logged in, forward to the front page
		if (!isloggedin()) forward(); 
		
	// choose the required canvas layout and items to display
	    $area2 = elgg_view_title(elgg_echo('thewire:add'));
	    $area2 .= elgg_view("thewire/forms/add");
	    $body = elgg_view_layout("two_column_left_sidebar", '',$area2);
		
	// Display page
		page_draw(elgg_echo('thewire:addpost'),$body);
		
?>