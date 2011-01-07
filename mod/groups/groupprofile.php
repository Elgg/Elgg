<?php
/**
 * Profile of a group
 * 
 * @package ElggGroups
 */

$guid = get_input('group_guid');
elgg_set_context('groups');

elgg_set_page_owner_guid($guid);

// can the user see all content
$group_access = group_gatekeeper(false);

// turn this into a core function
global $autofeed;
$autofeed = true;

$group = get_entity($guid);

elgg_push_breadcrumb(elgg_echo('groups:all'), elgg_get_site_url() . "pg/groups/world");
elgg_push_breadcrumb($group->name);

$sidebar = '';
$content = elgg_view('groups/profile/profile_block', array('entity' => $group));
if (group_gatekeeper(false)) {
	$content .= elgg_view('groups/profile/widgets', array('entity' => $group));
	$sidebar = elgg_view('groups/sidebar/members', array('entity' => $group));
} else {
	$content .= elgg_view('groups/profile/closed_membership');
}

$params = array(
	'content' => $content,
	'sidebar' => $sidebar,
	'title' => $group->name,
	'buttons' => elgg_view('groups/profile/buttons', array('entity' => $group)),
	'filter' => '',
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
