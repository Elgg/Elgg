<?php

	/**
	 * Elgg bookmarks plugin friends' page
	 * 
	 * @package ElggBookmarks
	 */

	// Start engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
	// List bookmarks
		$area2 = elgg_view_title(elgg_echo('bookmarks:friends'));
		set_context('search');
		// offset is grabbed by list_user_friends_objects()
		$area2 .= list_user_friends_objects(page_owner(),'bookmarks',10,false,false);
		set_context('bookmarks');
		
	// Format page
		$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2);
		
	// Draw it
		page_draw(elgg_echo('bookmarks:friends'),$body);

?>