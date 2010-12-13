<?php
/**
 * Main activity stream list page
 */

// $page_type comes from the page handler function

$options = array();

$type = get_input('type', 'all');
$subtype = get_input('subtype', '');
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
		$options['subject_guid'] = get_loggedin_userid();
		break;
	case 'friends':
		$title = elgg_echo('river:friends');
		$page_filter = 'friends';
		$options['relationship_guid'] = get_loggedin_userid();
		$options['relationship'] = 'friend';
		break;
	default:
		$title = elgg_echo('river:all');
		$page_filter = 'all';
		break;
}

$content = elgg_view('core/river/filter', array('selector' => $selector));

$content .= elgg_list_river($options);

$sidebar = elgg_view('core/river/sidebar');

$params = array(
	'content' =>  $content,
	'sidebar' => $sidebar,
	'buttons' => '',
	'filter_context' => $page_filter,
	'class' => 'elgg-river-layout',
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
