<?php

	/**
	 * Elgg blog add entry page
	 * 
	 * @package ElggBlog
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	// Load Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
	// If we're not logged in, forward to the front page
		if (!isloggedin()) forward(); 
		
	// Get the current page's owner
		$page_owner = page_owner();
		
	// Display page
		page_draw(elgg_echo('blog:addpost'),elgg_view("blog/forms/edit"));
		
?>