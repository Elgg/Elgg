<?php

	/**
	 * Elgg blog: delete post action
	 * 
	 * @package ElggBlog
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.org/
	 */

	// Make sure we're logged in (send us to the front page if not)
		gatekeeper();

	// Get input data
		$guid = (int) get_input('blogpost');
		
	// Make sure we actually have permission to edit
		$blog = get_entity($guid);
		if ($blog->getSubtype() == "blog" && $blog->canEdit()) {
	
		// Get owning user
				$owner = get_entity($blog->getOwner());
		// Delete it!
				$rowsaffected = $blog->delete();
				if ($rowsaffected > 0) {
		// Success message
					system_message(elgg_echo("blog:deleted"));
				} else {
					register_error(elgg_echo("blog:notdeleted"));
				}
		// Forward to the main blog page
				forward("mod/blog/?username=" . $owner->username);
		
		}
		
?>