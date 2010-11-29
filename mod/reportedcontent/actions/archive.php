<?php
/**
 * Elgg reported content: archive action
 * 
 * @package ElggReportedContent
 */

// Get input data
$guid = (int) get_input('guid');

// Make sure we actually have permission to edit
$report = get_entity($guid);
if ($report->getSubtype() == "reported_content" && $report->canEdit()) {
	// change the state
	if (!elgg_trigger_plugin_hook('reportedcontent:archive', 'system', array('report'=>$report), TRUE)) {
		system_message(elgg_echo("reportedcontent:notarchived"));
		forward('pg/admin/reportedcontent');
	}
	$report->state = "archived";

	// Success message
	system_message(elgg_echo("reportedcontent:archived"));

	// Forward back to the reported content page
	forward('pg/admin/reportedcontent');
}
