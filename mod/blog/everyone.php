<?php

	/**
	 * Elgg view all blog posts from all users page
	 * 
	 * @package ElggBlog
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	// Load Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

		if ($blogposts = get_entities('object','blog')) {
			$body = elgg_view("blog/view",array('posts' => $blogposts));
		}
		
	// Display page
		page_draw(elgg_echo('blog:everyone'),$body);
		
?>