<?php
/**
 * Elgg index page
 *
 */

/**
 * Start the Elgg engine
 */

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

//Load the front page
$content = elgg_view('sitepages/welcome');
$content .= elgg_view_title(elgg_echo('content:latest'));

elgg_set_context('search');
$offset = (int)get_input('offset', 0);
if(is_plugin_enabled('riverdashboard')){
	$items = elgg_view_river_items(0, 0, "", "", "", '', 10, 0, 0, true, false);
	$content .= elgg_view('riverdashboard/container', array('body' => $items . elgg_view('riverdashboard/js')));
}else{
	$content .= elgg_list_registered_entities(array('limit' => 10, 'offset' => $offset, 'full_view' => FALSE, 'allowed_types' => array('object','group')));
}
elgg_set_context('main');

global $autofeed;
$autofeed = FALSE;

$members = elgg_get_entities_from_metadata(array(
	'metadata_names' => 'icontime',
	'types' => 'user',
	'owner_guid' => 0,
	'limit' => 10
));
//include sidebar free text
$sidebar = elgg_view('sitepages/sidebar');
$sidebar .= elgg_view('sitepages/members', array('members' => $members));

$params = array(
	'content' => $content,
	'sidebar' => $sidebar
);
$content = elgg_view_layout('frontpage', $params);
echo elgg_view_page(null, $content);
