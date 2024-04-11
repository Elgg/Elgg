<?php
/**
 * Show all deleted items contained by the given group
 */

use Elgg\Exceptions\Http\PageNotFoundException;

if (!elgg_get_config('trash_enabled')) {
	throw new PageNotFoundException();
}

/* @var $group \ElggGroup */
$group = elgg_get_page_owner_entity();

elgg_push_entity_breadcrumbs($group);

echo elgg_view_page(elgg_echo('trash:group:title', [$group->getDisplayName()]), [
	'content' => elgg_view('trash/listing/group', ['entity' => $group]),
	'filter_id' => 'trash',
]);
