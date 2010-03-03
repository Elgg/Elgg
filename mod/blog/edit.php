<?php

	/**
	 * Elgg blog edit entry page
	 * 
	 * @package ElggBlog
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
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
		
	// Get the post, if it exists
		$blogpost = (int) get_input('blogpost');
		if ($post = get_entity($blogpost)) {
			
			if ($post->canEdit()) {
				
				$area1 = elgg_view_title(elgg_echo('blog:editpost'));
				$area1 .= elgg_view("blog/forms/edit", array('entity' => $post));
				$body = elgg_view_layout("edit_layout", $area1);
				
			}
			
		}
		
	// Display page
		page_draw(sprintf(elgg_echo('blog:editpost'),$post->title),$body);
		
?>