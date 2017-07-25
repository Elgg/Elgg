<?php
/**
 * Main activity stream list page
 */

$options = [
	'distinct' => false,
	'no_results' => elgg_echo('river:none'),
];

$page_type = preg_replace('[\W]', '', elgg_extract('page_type', $vars, 'all'));
$type = preg_replace('[\W]', '', get_input('type', 'all'));
$subtype = preg_replace('[\W]', '', get_input('subtype', ''));
if ($subtype) {
	$selector = "type=$type&subtype=$subtype";
} else {
	$selector = "type=$type";
}

if ($type != 'all') {
	$options['type'] = $type;
	if ($subtype) {
		$options['subtype'] = $subtype;
	}
}

switch ($page_type) {
	case 'mine':
		$title = elgg_echo('river:mine');
		$page_filter = 'mine';
		$options['subject_guid'] = elgg_get_logged_in_user_guid();
		break;
	case 'owner':
		$subject_username = elgg_extract('subject_username', $vars, '');
		$subject = get_user_by_username($subject_username);
		if (!$subject) {
			register_error(elgg_echo('river:subject:invalid_subject'));
			forward('');
		}
		elgg_set_page_owner_guid($subject->guid);
		$title = elgg_echo('river:owner', [htmlspecialchars($subject->name, ENT_QUOTES, 'UTF-8', false)]);
		$page_filter = 'subject';
		$options['subject_guid'] = $subject->guid;
		break;
	case 'friends':
		if (elgg_is_active_plugin('friends')) {
			$title = elgg_echo('river:friends');
			$page_filter = 'friends';
			$options['relationship_guid'] = elgg_get_logged_in_user_guid();
			$options['relationship'] = 'friend';
			break;
		}
	default:
		$title = elgg_echo('river:all');
		$page_filter = 'all';
		break;
}

$activity = elgg_list_river($options);

$content = elgg_view('core/river/filter', ['selector' => $selector]);

$sidebar = elgg_view('core/river/sidebar');

$body = elgg_view_layout('content', [
	'title' => $title,
	'content' =>  $content . $activity,
	'sidebar' => $sidebar,
	'filter_context' => $page_filter,
	'class' => 'elgg-river-layout',
]);

echo elgg_view_page($title, $body);
