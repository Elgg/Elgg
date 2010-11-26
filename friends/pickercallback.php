<?php
/**
 * Elgg friends picker callback
 *
 * @package Elgg
 * @subpackage Core
 */

// Load Elgg engine
require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
global $CONFIG;

// Get callback type (list or picker)
$type = get_input('type','picker');

// Get list of members if applicable
/*$members = get_input('members','');
if (!empty($members)) {
	$members = explode(',',$members);
} else {
	$members = array();
}*/
$collection = (int) get_input('collection',0);
$members = get_members_of_access_collection($collection, true);
if (!$members) {
	$members = array();
}

$friendspicker = (int) get_input('friendspicker',0);

// Get page owner (bomb out if there isn't one)
$pageowner = page_owner_entity();
if (!$pageowner) {
	forward();
	exit;
}

// Depending on the view type, launch a different view
switch($type) {
	case 'list':
		$js_segment = elgg_view('friends/tablelistcountupdate',array('friendspicker' => $friendspicker, 'count' => sizeof($members)));
		$content = elgg_view('friends/tablelist',array('entities' => $members, 'content' => $js_segment));
		break;
	default:
		$friends = $pageowner->getFriends('',9999);
		$content = elgg_view('friends/picker',array(
			'entities' => $friends,
			'value' => $members,
			'callback' => true,
			'friendspicker' => $friendspicker,
			'formcontents' => elgg_view('friends/forms/collectionfields',array('collection' => get_access_collection($collection))),
			'formtarget' => $CONFIG->wwwroot . 'action/friends/editcollection',
		));
		break;
}

// Output the content
echo $content;