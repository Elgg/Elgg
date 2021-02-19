<?php
/**
 * Renders a collection list item
 *
 * @uses $vars['item']       Access collection
 * @uses $vars['full_view']  Summary/full view flag
 */

$collection = elgg_extract('item', $vars);
if (!$collection instanceof ElggAccessCollection) {
	return;
}

$full_view = elgg_extract('full_view', $vars);

$metadata = false;
$count = $collection->getMembers(['count' => true]);
$subtitle = elgg_echo('friends:collection:member_count', [$count]);

if ($full_view) {
	$title = false;
	$content = elgg_view('collections/members', [
		'collection' => $collection,
	]);

	$collection_menu = elgg()->menus->getMenu('friends:collection', [
		'collection' => $collection,
	]);
	$items = $collection_menu->getSection('default');
	foreach ($items as $item) {
		if ($item->getName() == 'delete') {
			$item->addLinkClass('elgg-button elgg-button-delete');
		} else {
			$item->addLinkClass('elgg-button elgg-button-action');
		}
		elgg_register_menu_item('title', $item);
	}
} else {
	$title = elgg_view_url($collection->getURL(), $collection->name);
	
	$members = $collection->getMembers([
		'limit' => 10,
	]);
	$content = elgg_view_entity_list($members, [
		'list_type' => 'gallery',
		'size' => 'tiny',
		'gallery_class' => 'elgg-gallery-fluid elgg-gallery-users',
		'pagination' => false,
	]);
	$metadata = elgg_view_menu('friends:collection', [
		'collection' => $collection,
		'class' => 'elgg-menu-hz',
	]);
}

$params = [
	'collection' => $collection,
	'metadata' => $metadata,
	'title' => $title,
	'subtitle' => $subtitle,
	'content' => $content,
];

echo elgg_view('object/elements/summary/metadata', $params);
echo elgg_view('object/elements/summary/title', $params);
echo elgg_view('object/elements/summary/subtitle', $params);

echo elgg_format_element('div', [
	'class' => 'elgg-body clearfix',
], $content);
