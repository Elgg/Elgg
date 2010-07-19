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
		$subject_guid = $_SESSION['user']->guid;
		$relationship_type = '';
		$title_wording = elgg_echo('river:mine');
		break;
	case 'friends':
		$subject_guid = $_SESSION['user']->guid;
		$relationship_type = 'friend';
		$title_wording = elgg_echo('river:friends');
		break;
	default:
		$subject_guid = 0;
		$relationship_type = '';
		$title_wording = elgg_echo('river:all');
		break;
}

$title = elgg_view_title($title_wording);
$extend = elgg_view("activity/extend");
$river = elgg_view_river_items($subject_guid, 0, $relationship_type, $type, $subtype, '', 20, 0, 0, TRUE, FALSE);

// Replacing callback calls in the nav with something meaningless
$river = str_replace('callback=true', 'replaced=88,334', $river);

$nav = elgg_view('riverdashboard/nav',array('type' => $type,'subtype' => $subtype,'orient' => $orient));
if (isloggedin()) {
	$sidebar = elgg_view("riverdashboard/menu",array('type' => $type,'subtype' => $subtype,'orient' => $orient));
	$sidebar .= elgg_view("riverdashboard/sidebar", array("object_type" => 'riverdashboard'));
} else {
	$sidebar = '';
}

set_context('riverdashboard');

if (empty($callback)) {
	$body .= elgg_view('riverdashboard/container', array('body' => $nav . $river . elgg_view('riverdashboard/js')));
	page_draw($title_wording,elgg_view_layout('one_column_with_sidebar',$extend . $title . $body, $sidebar));
} else {
	header("Content-type: text/html; charset=UTF-8");
	echo $nav . $river . elgg_view('riverdashboard/js');
}
