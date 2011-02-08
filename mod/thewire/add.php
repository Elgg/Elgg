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
		if (!elgg_is_logged_in()) forward(); 
		
	// choose the required canvas layout and items to display
	    $area2 = elgg_view_title(elgg_echo('thewire:add'));
	    $area2 .= elgg_view("thewire/forms/add");
	    
	    $body = elgg_view_layout("one_sidebar", array('content' => $area2));
		
	// Display page
		echo elgg_view_page(elgg_echo('thewire:addpost'),$body);
		
?>