<?php

	/**
	 * Elgg blog add entry page
	 * 
	 * @package ElggBlog
	 */

	// Load Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		gatekeeper();
		
	// Get the current page's owner
		$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($_SESSION['guid']);
		}
		if ($page_owner instanceof ElggGroup)
			$container = $page_owner->guid;
			
	//set the title
		$area1 = elgg_view_title(elgg_echo('blog:addpost'));

	// Get the form
		$area1 .= elgg_view("blog/forms/edit", array('container_guid' => $container));
		
	// Display page
		page_draw(elgg_echo('blog:addpost'),elgg_view_layout("edit_layout", $area1));

		
?>