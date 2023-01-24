<?php

$group = elgg_get_page_owner_entity();

elgg_push_breadcrumb(elgg_echo('groups'), elgg_generate_url('collection:group:group:all'));
elgg_push_breadcrumb($group->getDisplayName(), $group->getURL());

echo elgg_view_page(elgg_echo('groups:edit'), [
	'content' => elgg_view('groups/edit', ['entity' => $group]),
	'filter_id' => 'groups/edit',
]);
