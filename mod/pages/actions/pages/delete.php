<?php
/**
 * Remove a page
 *
 * Subpages are not deleted but are moved up a level in the tree
 */

$guid = (int) get_input('guid');
$page = get_entity($guid);

if (!$page instanceof ElggPage || !$page->canDelete()) {
	return elgg_error_response(elgg_echo('pages:delete:failure'));
}

$container = $page->getContainerEntity();

// Bring all child elements forward
$parent_guid = $page->getParentGUID();

$children = elgg_get_entities([
	'type' => 'object',
	'subtype' => 'page',
	'metadata_name_value_pairs' => [
		'parent_guid' => $page->guid,
	],
	'limit' => false,
	'batch' => true,
	'batch_inc_offset' => false,
]);

/* @var $child ElggPage */
foreach ($children as $child) {
	$child->setParentByGUID($parent_guid);
}

if (!$page->delete()) {
	return elgg_error_response(elgg_echo('pages:delete:failure'));
}

// set forward url
$forward_url = '';

if (!empty($parent_guid)) {
	$parent = get_entity($parent_guid);
	if ($parent instanceof ElggPage) {
		$forward_url = $parent->getURL();
	}
}

if (empty($forward_url)) {
	if ($container instanceof ElggGroup) {
		$forward_url = elgg_generate_url('collection:object:page:group', ['guid' => $container->guid]);
	} else {
		$forward_url = elgg_generate_url('collection:object:page:owner', ['username' => $container->username]);
	}
}

return elgg_ok_response('', elgg_echo('pages:delete:success'), $forward_url);
