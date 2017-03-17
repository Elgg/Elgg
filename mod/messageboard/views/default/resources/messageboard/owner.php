<?php
/**
 * Elgg Message board index page
 *
 * @package MessageBoard
 */

elgg_require_js('elgg/messageboard');

$page_owner_guid = elgg_extract('page_owner_guid', $vars);
elgg_set_page_owner_guid($page_owner_guid);
$page_owner = elgg_get_page_owner_entity();
$history_username = elgg_extract('history_username', $vars);
$history_user = get_user_by_username($history_username);

elgg_push_breadcrumb($page_owner->name, $page_owner->getURL());

$options = [
	'annotations_name' => 'messageboard',
	'guid' => $page_owner_guid,
	'reverse_order_by' => true,
	'preload_owners' => true,
];

if ($history_user) {
	$options['annotations_owner_guid'] = $history_user->getGUID();
	$title = elgg_echo('messageboard:owner_history', [$history_user->name, $page_owner->name]);

	if ($page_owner instanceof ElggGroup) {
		$mb_url = "messageboard/group/$page_owner->guid/all";
	} else {
		$mb_url = "messageboard/owner/$page_owner->username";
	}
} else {
	$title = elgg_echo('messageboard:owner', [$page_owner->name]);
	$mb_url = '';
}

elgg_push_breadcrumb(elgg_echo('messageboard:board'), $mb_url);

if ($history_user) {
	elgg_push_breadcrumb($history_user->name);
}

$content = elgg_list_annotations($options);

if (!$content) {
	$content = elgg_echo('messageboard:none');
}

$vars = [
	'filter' => false,
	'content' => $content,
	'title' => $title,
	'reverse_order_by' => true
];

$body = elgg_view_layout('content', $vars);

echo elgg_view_page($title, $body);
