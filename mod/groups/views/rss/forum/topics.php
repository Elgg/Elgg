<?php
	/**
	 * Elgg groups plugin
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */
	 
	// If there are any topics to view, view them
		if (is_array($vars['entity']) && sizeof($vars['entity']) > 0) {		

			foreach($vars['entity'] as $topic) {

				echo elgg_view_entity($topic);
				
			}
		}
?>