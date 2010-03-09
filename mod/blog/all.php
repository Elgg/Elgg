<?php

	/**
	 * Elgg view all blog posts from all users page
	 */

	// Load Elgg engine
		define('everyoneblog','true');
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	
	// Get the current page's owner
		$page_owner = $_SESSION['user'];
		set_page_owner($_SESSION['guid']);
		
	//set blog title
		//$area1 = elgg_view('blog/blog_header', array('context' => "everyone", 'type' => 'blog'));
		set_context('search');
		$area2 .= "<div id='blogs'>" . list_entities('object','blog',0,10,false) . "<div class='clearfloat'></div></div>";
		set_context('blog');

	// Get categories, if they're installed
		global $CONFIG;
		//$area3 = elgg_view('blog/categorylist',array('baseurl' => $CONFIG->wwwroot . 'search/?subtype=blog&tagtype=universal_categories&tag=','subtype' => 'blog'));	
	//include a view for plugins to extend
		//$area3 .= elgg_view("blogs/favourite", array("object_type" => 'blog'));
	//get the latest comments on all blogs
		$comments = get_annotations(0, "object", "blog", "generic_comment", "", 0, 4, 0, "desc");
		//$area3 .= elgg_view('page_elements/latest_comments', array('comments' => $comments));		
	//include a view for plugins to extend
		//$area3 .= elgg_view("blogs/sidebar_options", array("object_type" => 'blog'));
		
		$area3 .= elgg_view('blog/stats');	
	
		$body = elgg_view_layout("one_column_with_sidebar", $area1.$area2, $area3);
		
	// Display page
		page_draw(elgg_echo('blog:all'),$body);
		
?>