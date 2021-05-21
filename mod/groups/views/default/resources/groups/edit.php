<?php

$guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'group', 'group', true);

/* @var $group \ElggGroup */
$group = get_entity($guid);

elgg_set_page_owner_guid($group->getGUID());

elgg_require_js('elgg/groups/edit');

elgg_push_breadcrumb(elgg_echo('groups'), elgg_generate_url('collection:group:group:all'));
elgg_push_breadcrumb($group->getDisplayName(), $group->getURL());

$content = elgg_view('groups/edit', ['entity' => $group]);

echo elgg_view_page(elgg_echo('groups:edit'), [
	'content' => $content,
	'filter_id' => 'groups/edit',
]);
