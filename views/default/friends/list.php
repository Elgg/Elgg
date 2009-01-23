<?php

	/**
	 * Elgg friends list
	 * Lists a user's friends
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['friends'] The array of ElggUser objects
	 */

		if (is_array($vars['friends']) && sizeof($vars['friends']) > 0) {
			
			foreach($vars['friends'] as $friend) {
				
				echo elgg_view_entity($friend);
				
			}
			
		}

?>