<?php

$guid = elgg_extract('guid', $vars);

$group = elgg_get_page_owner_entity();

elgg_push_breadcrumb(elgg_echo('groups'), "groups/all");
elgg_push_breadcrumb($group->getDisplayName(), $group->getURL());

// build page elements
$title = elgg_echo('groups:membershiprequests');

$requests = elgg_get_entities([
	'type' => 'user',
	'relationship' => 'membership_request',
	'relationship_guid' => $guid,
	'inverse_relationship' => true,
	'limit' => 0,
]);
$content = elgg_view('groups/membershiprequests', [
	'requests' => $requests,
	'entity' => $group,
]);

$tabs = elgg_view_menu('groups_members', [
	'entity' => $group,
	'class' => 'elgg-tabs'
]);

// build page
$body = elgg_view_layout('content', [
	'title' => $title,
	'content' => $content,
	'filter' => $tabs,
]);

// draw page
echo elgg_view_page($title, $body);
