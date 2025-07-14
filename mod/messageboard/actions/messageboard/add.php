<?php
/**
 * Elgg Message board: add message action
 */

use Elgg\Database\Clauses\OrderByClause;

$message_content = get_input('message_content');
$owner_guid = (int) get_input('owner_guid');
$owner = get_user($owner_guid);
$poster = elgg_get_logged_in_user_entity();

if (!$owner || empty($message_content)) {
	return elgg_error_response(elgg_echo('messageboard:blank'));
}

$result = $owner->annotate('messageboard', $message_content, $owner->access_id, $poster->guid);

if (!is_int($result)) {
	return elgg_error_response(elgg_echo('messageboard:failure'));
}

elgg_create_river_item([
	'view' => 'river/object/messageboard/create',
	'action_type' => 'messageboard',
	'subject_guid' => $poster->guid,
	'object_guid' => $owner->guid,
	'access_id' => $owner->access_id,
	'annotation_id' => $result,
]);

$output = elgg_list_annotations([
	'annotation_name' => 'messageboard',
	'guid' => $owner->guid,
	'pagination' => false,
	'order_by' => [
		new OrderByClause('a_table.time_created', 'DESC'),
		new OrderByClause('a_table.id', 'DESC'),
	],
	'limit' => 1,
]);

return elgg_ok_response($output, elgg_echo('messageboard:posted'));
