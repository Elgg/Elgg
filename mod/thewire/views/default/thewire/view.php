<?php

	/**
	 * Elgg thewire view page
	 * 
	 * @package ElggTheWire
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] An array of wire notes to view
	 * 
	 */
	 
	// If there are any wire notes to view, view them
		if (is_array($vars['entity']) && sizeof($vars['entity']) > 0) {
			
			foreach($vars['entity'] as $shout) {
				
				echo elgg_view_entity($shout);
				
			}
			
		}

?>