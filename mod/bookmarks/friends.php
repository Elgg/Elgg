<?php

	/**
	 * Elgg bookmarks plugin friends' page
	 * 
	 * @package ElggBookmarks
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.org/
	 */

	// Start engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
	// List bookmarks
		$area2 = elgg_view_title(elgg_echo('bookmarks:friends'));
		set_context('search');
		$area2 .= list_user_friends_objects(page_owner(),'bookmarks',10,false,false);
		set_context('bookmarks');
		
	// Format page
		$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2);
		
	// Draw it
		page_draw(elgg_echo('bookmarks:friends'),$body);

?>