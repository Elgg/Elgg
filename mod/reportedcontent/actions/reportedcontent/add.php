<?php
/**
 * Elgg report action
 */
$title = get_input('title');
$description = get_input('description');
$address = get_input('address');
$entity_guid = (int) get_input('entity_guid');

if (!$title || !$address) {
	return elgg_error_response(elgg_echo('reportedcontent:failed'), $address ?? REFERRER);
}

$report = new \ElggReportedContent();
$report->title = $title;
$report->address = elgg_normalize_site_url($address);
$report->description = $description;
$report->state = 'active';

if (!$report->save()) {
	return elgg_error_response(elgg_echo('reportedcontent:failed'), $address);
}

if (!empty($entity_guid)) {
	$report->addRelationship($entity_guid, 'reportedcontent');
}

return elgg_ok_response('', elgg_echo('reportedcontent:success'), $address);
