<?php
/**
 * Elgg reported content: archive action
 */

$guid = (int) get_input('guid');

$report = get_entity($guid);
if (!$report instanceof ElggReportedContent || !$report->canEdit()) {
	return elgg_error_response(elgg_echo('reportedcontent:notarchived'));
}

// change the state
$report->state = 'archived';

return elgg_ok_response('', elgg_echo('reportedcontent:archived'));
