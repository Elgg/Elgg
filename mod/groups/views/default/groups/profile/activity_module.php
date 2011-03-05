<?php
/**
 * Groups latest activity
 *
 * @todo add people joining group to activity
 * 
 * @package Groups
 */

if ($vars['entity']->activity_enable == 'no') {
	return true;
}

$group = $vars['entity'];
if (!$group) {
	return true;
}

$all_link = elgg_view('output/url', array(
	'href' => "pg/groups/activity/$group->guid",
	'text' => elgg_echo('link:view:all'),
));

$header = "<span class=\"group-widget-viewall\">$all_link</span>";
$header .= '<h3>' . elgg_echo('groups:activity') . '</h3>';


elgg_push_context('widgets');
$db_prefix = elgg_get_config('dbprefix');
$content = elgg_list_river(array(
	'limit' => 4,
	'pagination' => false,
	'joins' => array("join {$db_prefix}entities e1 on e1.guid = rv.object_guid"),
	'wheres' => array("(e1.container_guid = $group->guid)"),
));
elgg_pop_context();

if (!$content) {
	$content = '<p>' . elgg_echo('groups:activity:none') . '</p>';
}

echo elgg_view_module('info', '', $content, array('header' => $header));
