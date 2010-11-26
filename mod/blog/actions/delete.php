<?php

	/**
	 * Elgg blog: delete post action
	 * 
	 * @package ElggBlog
	 */

	// Make sure we're logged in (send us to the front page if not)
		gatekeeper();

	// Get input data
		$guid = (int) get_input('blogpost');
		
	// Make sure we actually have permission to edit
		$blog = get_entity($guid);
		if ($blog->getSubtype() == "blog" && $blog->canEdit()) {
	
		// Get container (user or group)
				$container = get_entity($blog->container_guid);
		// Delete it!
				$rowsaffected = $blog->delete();
				if ($rowsaffected > 0) {
		// Success message
					system_message(elgg_echo("blog:deleted"));
				} else {
					register_error(elgg_echo("blog:notdeleted"));
				}
		// Forward to the main blog page
				forward("pg/blog/owner/$container->username/");
		
		}
		
?>