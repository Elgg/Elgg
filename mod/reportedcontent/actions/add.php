<?php

	/**
	 * Elgg report action
	 * 
	 * @package ElggReportContent
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.org/
	 */

		$title = get_input('title');
		$description = get_input('description');
		$address = get_input('address');
		$access = ACCESS_PRIVATE; //this is private and only admins can see it
		
		if ($title && $address) {
			
			$report = new ElggObject;
			$report->subtype = "reported_content";
			$report->owner_guid = $_SESSION['user']->getGUID();
		    $report->title = $title;
		    $report->address = $address;
		    $report->description = $description;
		    $report->access_id = $access;
		
    		if ($report->save()) {
     			if (!trigger_plugin_hook('reportedcontent:add', 'system', array('report'=>$report), true)) {
     				$report->delete();
     				register_error(elgg_echo('reportedcontent:failed'));
     			} else {
	    			system_message(elgg_echo('reportedcontent:success'));
	    			$report->state = "active";
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