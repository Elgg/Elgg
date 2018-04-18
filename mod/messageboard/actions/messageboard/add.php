<?php
use Elgg\Database\Clauses\OrderByClause;

/**
 * Elgg Message board: add message action
 *
 * @package ElggMessageBoard
 */

$message_content = get_input('message_content');
$owner_guid = (int) get_input('owner_guid');
$owner = get_user($owner_guid);

if (!$owner || empty($message_content)) {
	return elgg_error_response(elgg_echo('messageboard:blank'));
}

$result = messageboard_add(elgg_get_logged_in_user_entity(), $owner, $message_content, $owner->access_id);

if (!$result) {
	return elgg_error_response(elgg_echo('messageboard:failure'));
}

$output = elgg_list_annotations([
	'annotations_name' => 'messageboard',
	'guid' => $owner->guid,
	'pagination' => false,
	'order_by' => [
		new OrderByClause('n_table.time_created', 'DESC'),
		new OrderByClause('n_table.id', 'DESC'),
	],
	'limit' => 1,
]);

return elgg_ok_response($output, elgg_echo('messageboard:posted'));
	
