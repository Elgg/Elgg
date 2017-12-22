<?php
/**
 * Elgg reported content: delete action
 */

$guid = (int) get_input('guid');

$report = get_entity($guid);
if (!$report instanceof ElggReportedContent || !$report->canDelete()) {
	return elgg_error_response(elgg_echo("reportedcontent:notdeleted"));
}

// give another plugin a chance to override
if (!elgg_trigger_plugin_hook('reportedcontent:delete', 'system', ['report' => $report], true)) {
	return elgg_error_response(elgg_echo("reportedcontent:notdeleted"));
}

if (!$report->delete()) {
	return elgg_error_response(elgg_echo("reportedcontent:notdeleted"));
}

return elgg_ok_response('', elgg_echo('reportedcontent:deleted'));
