<?php

	/**
	 * Elgg blog friends page
	 */

	// Load Elgg engine
		define('everyoneblog','true');
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
	// Get the current page's owner
		$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($_SESSION['guid']);
		}
		if (!($page_owner instanceof ElggEntity)) forward();

	//set blog title
		$area1 = elgg_view('blog/blog_header', array('context' => "friends", 'type' => 'blog'));
		
	// Get a list of blog posts
		set_context('search');
		$area2 .= "<div id='blogs'>" . list_user_friends_objects($page_owner->getGUID(),'blog',10,false) . "<div class='clearfloat'></div></div>";
		set_context('blog');
		
	// Get categories, if they're installed
		global $CONFIG;
		//$area3 .= elgg_view("blogs/favourite", array("object_type" => 'blog'));
		$comments = get_annotations(0, "object", "blog", "generic_comment", "", 0, 4, 0, "desc");
		$area3 .= elgg_view('page_elements/latest_comments', array('comments' => $comments));
	//include a view for plugins to extend
		$area3 .= elgg_view("blogs/sidebar_options", array("object_type" => 'blog'));
		
	// Display them in the page
        $body = elgg_view_layout("one_column_with_sidebar", $area1.$area2, $area3);
		
	// Display page
		page_draw(elgg_echo('blog:friends'),$body);
		
?>