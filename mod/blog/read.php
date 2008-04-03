<?php

	/**
	 * Elgg read blog post page
	 * 
	 * @package ElggBlog
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	// Load Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	// Get the specified blog post
		$post = (int) get_input('blogpost'); 
		if ($blogpost = get_entity($post)) {
			
	// Get any comments
			$comments = $blogpost->getAnnotations('comments');
		
	// Set the page owner
			set_page_owner($blogpost->getOwner());
			$page_owner = get_entity($blogpost->getOwner());
			
	// Display it
			$body = elgg_view("object/blog",array(
											'entity' => $blogpost,
											'entity_owner' => $page_owner,
											'comments' => $comments,
											'full' => true
											));
			
		}
		
	// Display page
		page_draw(sprintf(elgg_echo("blog:posttitle"),$page_owner->name,$blogpost->title),$body);
		
?>