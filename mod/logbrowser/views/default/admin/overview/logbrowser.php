<?php
/**
 * Elgg log browser admin page
 *
 * @note The ElggObject this creates for each entry is temporary
 * 
 * @package ElggLogBrowser
 */

$limit = get_input('limit', 40);
$offset = get_input('offset');

$search_username = get_input('search_username');
if ($search_username) {
	if ($user = get_user_by_username($search_username)) {
		$user = $user->guid;
	}
} else {
	$user_guid = get_input('user_guid',0);
	if ($user_guid) {
		$user = (int) $user_guid;
	} else {
		$user = "";
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

$title = elgg_view_title(elgg_echo('logbrowser'));

$form = elgg_view('logbrowser/form', array(
	'user_guid' => $user,
	'timeupper' => $timeupper,
	'timelower' => $timelower,
));

// Get log entries
$log = get_system_log($user, "", "", "","", $limit, $offset, false, $timeupper, $timelower);
$count = get_system_log($user, "", "", "","", $limit, $offset, true, $timeupper, $timelower);

$table = elgg_view('logbrowser/table', array('log_entries' => $log));

$nav = elgg_view('navigation/pagination',array(
	'offset' => $offset,
	'count' => $count,
	'limit' => $limit,
));

// display admin body
$body = <<<__HTML
$title
$form
<div class="admin_settings log_browser radius8 clearfix">
	$nav $table $nav
</div>
__HTML;

echo $body;
