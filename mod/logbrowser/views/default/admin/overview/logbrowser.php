<?php
/**
 * Elgg log browser.
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
$log_entries = array();

foreach ($log as $l) {
	$tmp = new ElggObject();
	$tmp->subtype = 'logwrapper';
	$tmp->entry = $l;
	$log_entries[] = $tmp;
}

$list = elgg_view_entity_list($log_entries, $count, $offset, $limit, false, false);

// display admin body
$body = <<<__HTML
$title
$form
<div class="admin_settings log_browser radius8 clearfloat">
	$list
</div>
__HTML;

echo $body;
