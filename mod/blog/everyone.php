<?php

	/**
	 * Elgg view all blog posts from all users page
	 * 
	 * @package ElggBlog
	 */

	// Load Elgg engine
		define('everyoneblog','true');
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	
		set_page_owner(get_loggedin_userid());

		$offset = (int)get_input('offset', 0);
		
		$area2 = elgg_view_title(elgg_echo('blog:everyone'));

		$area2 .= elgg_list_entities(array('type' => 'object', 'subtype' => 'blog', 'limit' => 10, 'offset' => $offset, 'full_view' => FALSE));

		// get tagcloud
		// $area3 = "This will be a tagcloud for all blog posts";

		// Get categories, if they're installed
		global $CONFIG;
		
		$area3 = elgg_view('blog/categorylist', array(
			'baseurl' => $CONFIG->wwwroot . 'pg/categories/list/?subtype=blog&owner_guid='.$page_owner->guid.'&category=',
			'subtype' => 'blog'
		));

		$body = elgg_view_layout("two_column_left_sidebar", '', $area2, $area3);
		
	// Display page
		page_draw(elgg_echo('blog:everyone'),$body);
		
?>