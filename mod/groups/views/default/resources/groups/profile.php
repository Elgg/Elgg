<?php

$guid = elgg_extract('guid', $vars);

elgg_register_rss_link();

elgg_entity_gatekeeper($guid, 'group');

$group = get_entity($guid);
/* @var $group ElggGroup */

elgg_push_context('group_profile');

elgg_push_breadcrumb(elgg_echo('groups'), elgg_generate_url('collection:group:group:all'));

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
