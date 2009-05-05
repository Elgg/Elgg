<?php

	/**
	 * Elgg report action
	 * 
	 * @package ElggReportContent
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

		$title = get_input('title');
		$description = get_input('description');
		$address = get_input('address');
		$access = ACCESS_PRIVATE; //this is private and only admins can see it
		
		if ($title && $address) {
			
			$entity = new ElggObject;
			$entity->subtype = "reported_content";
			$entity->owner_guid = $_SESSION['user']->getGUID();
		    $entity->title = $title;
		    $entity->address = $address;
		    $entity->description = $description;
		    $entity->access_id = $access;
		
    		if ($entity->save()) {
     			if (!trigger_plugin_hook('reportedcontent:add', $reported->type, array('entity'=>$reported), true)) {
     				$entity->delete();
     				register_error(elgg_echo('reportedcontent:failed'));
     			} else {
	    			system_message(elgg_echo('reportedcontent:success'));
	    			$entity->state = "active";
     			}
    			forward($address);
    		} else {
    			register_error(elgg_echo('reportedcontent:failed'));
    			forward($address);
    		}
    		
		} else {
    		
    		register_error(elgg_echo('reportedcontent:failed'));
    	    forward($address);
    	    
	    }

?>