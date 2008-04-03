<?php

	/**
	 * Elgg blog: add comment action
	 * 
	 * @package ElggBlog
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	// Make sure we're logged in; forward to the front page if not
		if (!isloggedin()) forward();
		
	// Get input
		$blogpost_guid = (int) get_input('blogpost_guid');
		$comment = get_input('comment');
		
	// Let's see if we can get an entity with the specified GUID, and that it's a blog post
		if ($blogpost = get_entity($blogpost_guid)) {
			if ($blogpost->getSubtype() == "blog") {
				
	// If posting the comment was successful, say so
				if ($blogpost->annotate('comment',$comment,$blogpost->access_id, $_SESSION['guid'])) {
					
					system_message(elgg_echo("comment:success"));
					
				} else {
					system_message(elgg_echo("comment:failure"));
				}
			
			}
				
		} else {
		
			system_message(elgg_echo("blog:notfound"));
			
		}
		
	// Forward to the 
		forward("mod/blog/read.php?blogpost=" . $blogpost_guid);

?>