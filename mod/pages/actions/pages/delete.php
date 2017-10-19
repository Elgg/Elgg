<?php
/**
 * Remove a page
 *
 * Subpages are not deleted but are moved up a level in the tree
 */

$guid = (int) get_input('guid');
$page = get_entity($guid);
/* @var ElggObject $page */

elgg_load_library('elgg:pages');

if (!pages_is_page($page) || !pages_can_delete_page($page)) {
	return elgg_error_response(elgg_echo('pages:delete:failure'));
}

$container = $page->getContainerEntity();

// Bring all child elements forward
$parent_guid = $page->parent_guid;

$children = new ElggBatch('elgg_get_entities_from_metadata', [
	'metadata_name' => 'parent_guid',
	'metadata_value' => $page->guid,
	'limit' => 0,
]);

$db_prefix = elgg_get_config('dbprefix');
$subtype_id = (int) get_subtype_id('object', 'page_top');

foreach ($children as $child) {
	if ($parent_guid) {
		$child->parent_guid = $parent_guid;
		continue;
	}

	// If no parent, we need to transform $child to a page_top
	$child_guid = (int) $child->guid;

	update_data("
		UPDATE {$db_prefix}entities
		SET subtype = $subtype_id
		WHERE guid = $child_guid
	");

	elgg_delete_metadata([
		'guid' => $child_guid,
		'metadata_name' => 'parent_guid',
	]);

	_elgg_invalidate_cache_for_entity($child_guid);
	_elgg_invalidate_memcache_for_entity($child_guid);
}

if (!$page->delete()) {
	return elgg_error_response(elgg_echo('pages:delete:failure'));
}

if (elgg_instanceof($container, 'group')) {
	$forward_url = "pages/group/{$container->guid}/all";
} else {
	$forward_url = "pages/owner/{$container->username}";
}

if ($parent_guid) {
	$parent = get_entity($parent_guid);
	if ($parent) {
		$forward_url = $parent->getURL();
	}
}

return elgg_ok_response('', elgg_echo('pages:delete:success'), $forward_url);
