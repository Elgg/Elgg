<?php

	/**
	 * Elgg Groups add a forum topic page
	 * 
	 * @package ElggGroups
	 */

	// Load Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
		gatekeeper();
		
		$page_owner = set_page_owner((int) get_input('group_guid'));
		
		if (!(page_owner_entity() instanceof ElggGroup)) forward();
		
	// sort the display
	    $area2 = elgg_view("forms/forums/addtopic");
	    $body = elgg_view_layout('two_column_left_sidebar',$area1, $area2);
		
	// Display page
		page_draw(elgg_echo('groups:addtopic'),$body);
		
?>