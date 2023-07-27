<?php

$group = elgg_get_page_owner_entity();

elgg_push_entity_breadcrumbs($group);

echo elgg_view_page(elgg_echo('groups:edit'), [
	'content' => elgg_view('groups/edit', ['entity' => $group]),
	'filter_id' => 'groups/edit',
]);
