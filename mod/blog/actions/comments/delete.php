<?php

	/**
	 * Elgg blog: delete comment action
	 * 
	 * @package ElggBlog
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	// Ensure we're logged in
		if (!isloggedin()) forward();
		
	// Make sure we can get the comment in question
		$comment_id = (int) get_input('comment_id');
		if ($comment = get_annotation($comment_id)) {
			
			$url = "mod/blog/read.php?blogpost=" . $comment->entity_guid;
			if ($comment->canEdit()) {
				$comment->delete();
				system_message(elgg_echo("comment:deleted"));
				forward($url);
			}
			
		} else {
			$url = "";
		}
		
		system_message(elgg_echo("comment:notdeleted"));
		forward($url);

?>