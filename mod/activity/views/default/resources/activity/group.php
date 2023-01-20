<?php

$page_owner = elgg_get_page_owner_entity();

elgg_group_tool_gatekeeper('activity');

// can't use elgg_push_collection_breadcrumbs() because of the routenames for river
elgg_push_breadcrumb($page_owner->getDisplayName(), $page_owner->getURL());
elgg_push_breadcrumb(elgg_echo('collection:river'), elgg_generate_url('collection:river:group', ['guid' => $page_owner->guid]));

$content = elgg_view('river/listing/group', [
	'entity' => $page_owner,
	'entity_type' => preg_replace('[\W]', '', get_input('type', 'all')),
	'entity_subtype' => preg_replace('[\W]', '', get_input('subtype', '')),
	'show_filter' => true,
]);

echo elgg_view_page(elgg_echo('collection:river:group'), [
	'content' => $content,
	'class' => 'elgg-river-layout',
	'filter_id' => 'river/group',
]);
