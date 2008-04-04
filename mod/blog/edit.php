<?php

	/**
	 * Elgg blog edit entry page
	 * 
	 * @package ElggBlog
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	// Load Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
	// Get the post, if it exists
		$blogpost = (int) get_input('blogpost');
		if ($post = get_entity($blogpost)) {
			
			if ($post->canEdit()) {
				
				$body = elgg_view("blog/forms/edit", array('entity' => $post));
				
			}
			
		}
		
	// Display page
		page_draw(sprintf(elgg_echo('blog:editpost'),$post->title),$body);
		
?>