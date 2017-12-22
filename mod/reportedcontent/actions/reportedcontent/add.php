<?php
/**
 * Elgg report action
 */
$title = get_input('title');
$description = get_input('description');
$address = get_input('address');
$access = ACCESS_PRIVATE; //this is private and only admins can see it

$fail = function () use ($address) {
	return elgg_error_response(elgg_echo('reportedcontent:failed'), $address);
};

if (!$title || !$address) {
	$fail();
}

$report = new ElggReportedContent();
$report->owner_guid = elgg_get_logged_in_user_guid();
$report->title = $title;
$report->address = $address;
$report->description = $description;
$report->access_id = $access;

if (!$report->save()) {
	$fail();
}

if (!elgg_trigger_plugin_hook('reportedcontent:add', 'system', ['report' => $report], true)) {
	$report->delete();
	$fail();
}

$report->state = 'active';

return elgg_ok_response('', elgg_echo('reportedcontent:success'), $address);
