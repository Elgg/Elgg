<?php
/**
 * Add bookmark page
 */

use Elgg\Exceptions\Http\EntityPermissionsException;

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner->canWriteToContainer(0, 'object', 'bookmarks')) {
	throw new EntityPermissionsException();
}

elgg_push_collection_breadcrumbs('object', 'bookmarks', $page_owner);

echo elgg_view_page(elgg_echo('add:object:bookmarks'), [
	'filter_id' => 'bookmarks/edit',
	'content' => elgg_view_form('bookmarks/save', ['sticky_enabled' => true]),
]);
