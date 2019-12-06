<?php

elgg_require_js('elgg/groups/edit');

elgg_push_breadcrumb(elgg_echo('groups'), "groups/all");

$guid = elgg_extract('guid', $vars);
$group = get_entity($guid);

if ($group instanceof ElggGroup && $group->canEdit()) {
	elgg_set_page_owner_guid($group->getGUID());
	elgg_push_breadcrumb($group->getDisplayName(), $group->getURL());
	$content = elgg_view("groups/edit", ['entity' => $group]);
} else {
	$content = elgg_echo('groups:noaccess');
}

echo elgg_view_page(elgg_echo('groups:edit'), [
	'content' => $content,
]);
