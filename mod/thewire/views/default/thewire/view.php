<?php

	/**
	 * Elgg thewire view page
	 * 
	 * @package ElggTheWire
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