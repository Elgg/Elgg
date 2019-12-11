<?php

use Elgg\Database\Clauses\OrderByClause;

$group = elgg_get_page_owner_entity();

elgg_push_breadcrumb(elgg_echo('groups'), elgg_generate_url('collection:group:group:all'));
elgg_push_breadcrumb($group->getDisplayName(), $group->getURL());

// build page elements
$title = elgg_echo('groups:membershiprequests');

$content = elgg_list_relationships([
	'relationship' => 'membership_request',
	'relationship_guid' => $group->guid,
	'inverse_relationship' => true,
	'order_by' => new OrderByClause('er.time_created', 'ASC'),
	'no_results' => elgg_echo('groups:requests:none'),
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
