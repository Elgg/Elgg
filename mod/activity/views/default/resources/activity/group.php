<?php

use Elgg\Exceptions\Http\EntityNotFoundException;

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner instanceof ElggGroup) {
	throw new EntityNotFoundException(elgg_echo('river:subject:invalid_subject'));
}

elgg_group_tool_gatekeeper('activity');

// can't use elgg_push_collection_breadcrumbs() because of the routenames for river
elgg_push_breadcrumb($page_owner->getDisplayName(), $page_owner->getURL());
elgg_push_breadcrumb(elgg_echo('collection:river'), elgg_generate_url('collection:river:group', ['guid' => $page_owner->guid]));

// get filter options
$type = preg_replace('[\W]', '', get_input('type', 'all'));
$subtype = preg_replace('[\W]', '', get_input('subtype', ''));

// build page content
$content = elgg_view('river/listing/group', [
	'entity' => $page_owner,
	'entity_type' => $type,
	'entity_subtype' => $subtype,
	'show_filter' => true,
]);

echo elgg_view_page(elgg_echo('collection:river:group'), [
	'content' => $content,
	'class' => 'elgg-river-layout',
	'filter_id' => 'river/group',
]);
