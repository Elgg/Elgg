<?php

	/**
	 * Elgg blog aggregate comments view
	 * 
	 * @package ElggBlog
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['comments'] Array of comments
	 */

		if (isset($vars['comments']) && is_array($vars['comments']) && sizeof($vars['comments']) > 0) {
			
			echo "<h3>". elgg_echo("comments") ."</h3><ol>";
			foreach($vars['comments'] as $comment) {
				
				echo elgg_view("object/blog-comment",array('entity' => $comment));
				
			}
			echo "</ol>";
			
		}

?>