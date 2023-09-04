<?php

elgg_register_rss_link();

/* @var $group ElggGroup */
$group = elgg_get_page_owner_entity();

elgg_push_context('group_profile');

elgg_push_entity_breadcrumbs($group);

$sidebar = '';
if ($group->canAccessContent()) {
	$sidebar .= elgg_view('groups/sidebar/search', ['entity' => $group]);
	$sidebar .= elgg_view('groups/sidebar/owner', ['entity' => $group]);
	$sidebar .= elgg_view('groups/sidebar/members', ['entity' => $group]);
} else {
	$sidebar .= elgg_view('groups/sidebar/owner', ['entity' => $group]);
}

echo elgg_view_page($group->getDisplayName(), [
	'content' => elgg_view('groups/profile/layout', ['entity' => $group]),
	'sidebar' => $sidebar,
	'entity' => $group,
	'filter' => false,
]);
