<?php
use Elgg\SystemLog\SystemLog;

/**
 * Elgg log browser admin page
 */

$limit = get_input('limit', 20);
$offset = get_input('offset');

$search_username = get_input('search_username');
if ($search_username) {
	$user = get_user_by_username($search_username);
	if ($user) {
		$user_guid = $user->guid;
	} else {
		$user_guid = null;
	}
} else {
	$user_guid = get_input('user_guid', null);
	if ($user_guid) {
		$user_guid = (int) $user_guid;
		$user = get_entity($user_guid);
		if ($user) {
			$search_username = $user->username;
		}
	} else {
		$user_guid = null;
	}
}

$timelower = get_input('timelower');
if ($timelower) {
	$timelower = strtotime($timelower);
}

$timeupper = get_input('timeupper');
if ($timeupper) {
	$timeupper = strtotime($timeupper);
}

$ip_address = get_input('ip_address');
$object_id = get_input('object_id');

$refine = elgg_view('logbrowser/refine', [
	'timeupper' => $timeupper,
	'timelower' => $timelower,
	'ip_address' => $ip_address,
	'username' => $search_username,
	'object_id' => $object_id,
]);

// Get log entries
$options = [
	'performed_by_guid' => $user_guid,
	'limit' => $limit,
	'offset' => $offset,
	'count' => false,
	'created_before' => $timeupper,
	'created_after' => $timelower,
	'ip_address' => $ip_address,
	'object_id' => $object_id,
];
$log = SystemLog::instance()->getAll($options);

$options['count'] = true;
$count = SystemLog::instance()->getAll($options);

// if user does not exist, we have no results
if ($search_username && is_null($user_guid)) {
	$log = false;
	$count = 0;
}

$table = elgg_view('logbrowser/table', ['log_entries' => $log]);

$nav = elgg_view('navigation/pagination', [
	'offset' => $offset,
	'count' => $count,
	'limit' => $limit,
]);

// display admin body
$body = <<<__HTML
$refine
$nav
$table
$nav
__HTML;

echo $body;
