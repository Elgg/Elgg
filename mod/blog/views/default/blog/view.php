<?php

	/**
	 * Elgg blog view page
	 * 
	 * @package ElggBlog
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['posts'] An array of posts to view
	 */

	// If there are any posts to view, view them
		if (is_array($vars['posts']) && sizeof($vars['posts']) > 0) {
			
			foreach($vars['posts'] as $post) {
				
				echo elgg_view_entity($post);
				
			}
			
		}

?>