<?php
/**
 * Elgg groups forum
 */

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

$group_guid = (int)get_input('group_guid');
set_page_owner($group_guid);
if (!(elgg_get_page_owner() instanceof ElggGroup)) {
	forward();
}

group_gatekeeper();

//get any forum topics
$options = array(
	'type' => 'object',
	'subtype' => 'groupforumtopic',
	'limit' => 20,
	'order_by' => 'e.last_action desc',
	'container_guid' => $group_guid,
	'fullview' => FALSE
);

//$topics = elgg_list_entities_from_annotations($options);
$topics = elgg_list_entities($options);

// set up breadcrumbs
$group = get_entity($group_guid);
elgg_push_breadcrumb(elgg_echo('groups'), elgg_get_site_url()."pg/groups/world/");
elgg_push_breadcrumb($group->name, $group->getURL());
elgg_push_breadcrumb(elgg_echo('item:object:groupforumtopic'));

$area1 = elgg_view('navigation/breadcrumbs');

$area1 .= elgg_view("forum/topics", array('topics' => $topics, 'group_guid' => $group_guid));
elgg_set_context('groups');

$body = elgg_view_layout('one_column_with_sidebar', array('content' => $area1));

$title = elgg_echo('item:object:groupforumtopic');

// Finally draw the page
echo elgg_view_page($title, $body);