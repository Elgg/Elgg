<?php

	/**
	 * Elgg Groups topic posts page
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	// Load Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
	// We now have RSS on topics
		global $autofeed;
		$autofeed = true;
		
	//get_input('group_guid');
		set_page_owner(get_input('group_guid'));
		if (!(page_owner_entity() instanceof ElggGroup)) forward();
		
		group_gatekeeper();
		
    // get the entity from id
        $topic = get_entity(get_input('topic'));
        if (!$topic) forward();
         
    // Display them
	    $area2 = elgg_view("forum/viewposts", array('entity' => $topic));
	    $body = elgg_view_layout("two_column_left_sidebar", '' , $area2);
		
	// Display page
		page_draw($topic->title,$body);
		
?>