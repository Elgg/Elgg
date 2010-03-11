<?php

	/**
	 * Elgg reported content: archive action
	 * 
	 * @package ElggReportedContent
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.org/
	 */

	// Make sure we're logged in and are admin
	    admin_gatekeeper();

	// Get input data
		$guid = (int) get_input('item');
		
	// Make sure we actually have permission to edit
		$report = get_entity($guid);
		if ($report->getSubtype() == "reported_content" && $report->canEdit()) {
	
		// change the state
				if (!trigger_plugin_hook('reportedcontent:archive', 'system', array('report'=>$report), true)) {
 					system_message(elgg_echo("reportedcontent:notarchived"));
 					forward("pg/reportedcontent/");
		 		}
		        $report->state = "archived";
				
		// Success message
				system_message(elgg_echo("reportedcontent:archived"));
				
		// Forward back to the reported content page
				forward("pg/reportedcontent/");
		
		}
		
?>