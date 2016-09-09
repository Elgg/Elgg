<?php
/**
 * Remove a page
 *
 * Subpages are not deleted but are moved up a level in the tree
 *
 * @package ElggPages
 */

$guid = get_input('guid');
$page = get_entity($guid);
/* @var ElggObject $page */

elgg_load_library('elgg:pages');

if (!pages_is_page($page) || !pages_can_delete_page($page)) {
	register_error(elgg_echo('pages:delete:failure'));
	forward(REFERER);
}

$container = $page->getContainerEntity();

// Bring all child elements forward
$parent = $page->parent_guid;

$children = new ElggBatch('elgg_get_entities_from_metadata', [
	'metadata_name' => 'parent_guid',
	'metadata_value' => $page->guid,
	'limit' => 0,
]);

$db_prefix = elgg_get_config('dbprefix');
$subtype_id = (int)get_subtype_id('object', 'page_top');

foreach ($children as $child) {
	if ($parent) {
		$child->parent_guid = $parent;
		continue;
	}

	// If no parent, we need to transform $child to a page_top
	$child_guid = (int)$child->guid;

	update_data("
		UPDATE {$db_prefix}entities
		SET subtype = $subtype_id
		WHERE guid = $child_guid
	");

	elgg_delete_metadata(array(
		'guid' => $child_guid,
		'metadata_name' => 'parent_guid',
	));

	_elgg_invalidate_cache_for_entity($child_guid);
	_elgg_invalidate_memcache_for_entity($child_guid);
}

if (!$page->delete()) {
	register_error(elgg_echo('pages:delete:failure'));
	forward(REFERER);
}

system_message(elgg_echo('pages:delete:success'));
if ($parent) {
	$parent = get_entity($parent);
	if ($parent) {
		forward($parent->getURL());
	}
}

if (elgg_instanceof($container, 'group')) {
	forward("pages/group/$container->guid/all");
} else {
	forward("pages/owner/$container->username");
}
