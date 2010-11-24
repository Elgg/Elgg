<?php
/**
 * Elgg reported content: delete action
 * 
 * @package ElggReportedCOntent
 */

// Get input data
$guid = (int) get_input('item');

// Make sure we actually have permission to edit
$report = get_entity($guid);
if ($report->getSubtype() == "reported_content" && $report->canEdit()) {
	// Delete it!
	if (!elgg_trigger_plugin_hook('reportedcontent:delete', '$system', array('report'=>$report), true)) {
		register_error(elgg_echo("reportedcontent:notdeleted"));
		forward('pg/admin/reportedcontent');
	}

	$rowsaffected = $report->delete();
	if ($rowsaffected > 0) {
		// Success message
		system_message(elgg_echo("reportedcontent:deleted"));
	} else {
		register_error(elgg_echo("reportedcontent:notdeleted"));
	}
	
	// Forward back to the reported content page
	forward('pg/admin/reportedcontent');
}
