<?php

	/**
	 * Elgg user display (small)
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity
	 */
	 
	    //grab the users status message with metadata 'state' set to current if it exists
		if($get_status = get_entities_from_metadata("state", "current", "object", "status", $vars['entity']->guid)){
    		    
            foreach($get_status as $s) {
	            $info = elgg_view("status/friends_view", array('entity' => $s));
            }
    		    
		}


		$icon = elgg_view(
				"profile/icon", array(
										'entity' => $vars['entity'],
										'size' => 'small',
									  )
			);
	
		$info .= "<p><b><a href=\"" . $vars['entity']->getUrl() . "\">" . $vars['entity']->name . "</a></b></p>";
	
		$location = $vars['entity']->location;
		if (!empty($location)) {
			$info .= "<p>" . elgg_echo("profile:location") . ": " . elgg_view("output/tags",array('value' => $vars['entity']->location)) . "</p>";
		}
		
		echo elgg_view_listing($icon, $info);
			
?>