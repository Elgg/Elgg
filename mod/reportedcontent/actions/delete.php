<?php
/**
 * Elgg reported content: delete action
 * 
 * @package ElggReportedContent
 */

$guid = (int) get_input('guid');

$report = get_entity($guid);

// Make sure we actually have permission to delete
if ($report->getSubtype() == "reported_content" && $report->canEdit()) {

	// give another plugin a chance to override
	if (!elgg_trigger_plugin_hook('reportedcontent:delete', 'system', array('report' => $report), TRUE)) {
		register_error(elgg_echo("reportedcontent:notdeleted"));
		forward(REFERER);
	}

	if ($report->delete()) {
		system_message(elgg_echo("reportedcontent:deleted"));
	} else {
		register_error(elgg_echo("reportedcontent:notdeleted"));
	}
	
	forward(REFERER);
}
