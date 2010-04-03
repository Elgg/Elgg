<?php

	/**
	 * Elgg read blog post page
	 * 
	 * @package ElggBlog
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	// Load Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	// Get the specified blog post
		$post = (int) get_input('blogpost');

	// If we can get out the blog post ...
		if ($blogpost = get_entity($post)) {
			
	// Get any comments
			//$comments = $blogpost->getAnnotations('comments');
		
	// Set the page owner
			if ($blogpost->container_guid) {
				set_page_owner($blogpost->container_guid);
			} else {
				set_page_owner($blogpost->owner_guid);
			}

	$page_owner = page_owner_entity();
			
	// Display it
			$area2 = elgg_view_entity($blogpost, true);
			/*$area2 = elgg_view("object/blog",array(
											'entity' => $blogpost,
											'entity_owner' => $page_owner,
											'comments' => $comments,
											'full' => true
											));
			*/								
	// Set the title appropriately
		$title = sprintf(elgg_echo("blog:posttitle"),$page_owner->name,$blogpost->title);

	// Display through the correct canvas area
		$body = elgg_view_layout("two_column_left_sidebar", '', $area1 . $area2);
			
	// If we're not allowed to see the blog post
		} else {
			
	// Display the 'post not found' page instead
			$body = elgg_view("blog/notfound");
			$title = elgg_echo("blog:notfound");
			
		}
		
	// Display page
		page_draw($title,$body);
		
?>