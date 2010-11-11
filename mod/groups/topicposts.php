<?php

	/**
	 * Elgg Groups topic posts page
	 * 
	 * @package ElggGroups
	 */

	// Load Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
	// We now have RSS on topics
		global $autofeed;
		$autofeed = true;
		
	//get_input('group_guid');
		set_page_owner(get_input('group_guid'));
		if (!(elgg_get_page_owner() instanceof ElggGroup)) forward();
		
		group_gatekeeper();
		
    // get the entity from id
        $topic = get_entity(get_input('topic'));
        if (!$topic) forward();
         
    // Display them
	    $area2 = elgg_view("forum/viewposts", array('entity' => $topic));
	    $body = elgg_view_layout("one_column_with_sidebar", array('content' => $area2));
		
	// Display page
		echo elgg_view_page($topic->title,$body);
		
?>