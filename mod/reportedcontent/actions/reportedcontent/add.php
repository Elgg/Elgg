<?php
/**
 * Elgg report action
 */
$title = get_input('title');
$description = get_input('description');
$address = get_input('address');

if (!$title || !$address) {
	return elgg_error_response(elgg_echo('reportedcontent:failed'), $address);
}

$report = new \ElggReportedContent();
$report->owner_guid = elgg_get_logged_in_user_guid();
$report->title = $title;
$report->address = $address;
$report->description = $description;

if (!$report->save()) {
	return elgg_error_response(elgg_echo('reportedcontent:failed'), $address);
}

$report->state = 'active';

$entity_guid = (int) get_input('entity_guid');
if ($entity_guid) {
	$report->addRelationship($entity_guid, 'reportedcontent');
}

return elgg_ok_response('', elgg_echo('reportedcontent:success'), $address);
