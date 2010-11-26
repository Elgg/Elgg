<?php

	/**
	 * Elgg thewire index page
	 * 
	 * @package Elggthewire
	 */

	// Load Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
	// Get the current page's owner
		$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($page_owner->getGUID());
		}

		if (get_loggedin_userid() == $page_owner->guid) {
			$title = elgg_echo('thewire:yours');
		} else {
			$title = sprintf(elgg_echo('thewire:theirs'), $page_owner->name);
		}

	// title
	    $area2 = elgg_view_title($title);
	    
	//add form
		$area2 .= elgg_view("thewire/forms/add");
	    
	// Display the user's wire
		$area2 .= list_user_objects($page_owner->getGUID(),'thewire');
    
    //select the correct canvas area
	    $body = elgg_view_layout("two_column_left_sidebar", '', $area2);
		
	// Display page
		page_draw($title ,$body);
		
?>