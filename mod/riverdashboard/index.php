<?php

/**
 * Elgg river dashboard plugin index page
 */

require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');

$type = get_input('type');
$subtype = get_input('subtype');
$orient = get_input('display');
if(!$orient) {
	$orient = 'all';
}
$callback = get_input('callback');

if ($type == 'all') {
	$type = '';
	$subtype = '';
}

$body = '';

switch($orient) {
	case 'mine':
		$subject_guid = get_loggedin_userid();
		$relationship_type = '';
		$title_wording = elgg_echo('river:mine');
		break;
	case 'friends':
		$subject_guid = get_loggedin_userid();
		$relationship_type = 'friend';
		$title_wording = elgg_echo('river:friends');
		break;
	default:
		$subject_guid = 0;
		$relationship_type = '';
		$title_wording = elgg_echo('river:all');
		break;
}

$title .= elgg_view_title($title_wording);
$extend = elgg_view("activity/extend");
$river = riverdashboard_view_river_items($subject_guid, 0, $relationship_type, $type, $subtype, '', 20, 0, 0, TRUE);

// Replacing callback calls in the nav with something meaningless
$river = str_replace('callback=true', 'replaced=88,334', $river);

$nav = elgg_view('riverdashboard/nav',array('type' => $type,'subtype' => $subtype,'orient' => $orient));
if (isloggedin()) {
	$sidebar = elgg_view("riverdashboard/menu",array('type' => $type,'subtype' => $subtype,'orient' => $orient));
	$sidebar .= elgg_view("riverdashboard/sidebar", array("object_type" => 'riverdashboard'));
} else {
	$sidebar = '';
}

elgg_set_context('riverdashboard');

if (empty($callback)) {
	$body .= elgg_view('riverdashboard/container', array('body' => $nav . $extend . $river . elgg_view('riverdashboard/js')));
	$params = array(
		'content' => $title . $body,
		'sidebar' => $sidebar
	);
	$body = elgg_view_layout('one_column_with_sidebar', $params);
	echo elgg_view_page($title_wording, $body);
} else {
	header("Content-type: text/html; charset=UTF-8");
	echo $nav . $river . elgg_view('riverdashboard/js');
}
