<?php
/**
 * Elgg reported content: archive action
 * 
 * @package ElggReportedContent
 */

$guid = (int) get_input('guid');

$report = get_entity($guid);

// Make sure we actually have permission to edit
if ($report->getSubtype() == "reported_content" && $report->canEdit()) {

	// allow another plugin to override
	if (!elgg_trigger_plugin_hook('reportedcontent:archive', 'system', array('report' => $report), TRUE)) {
		system_message(elgg_echo("reportedcontent:notarchived"));
		forward(REFERER);
	}

	// change the state
	$report->state = "archived";

	system_message(elgg_echo("reportedcontent:archived"));

	forward(REFERER);
}
