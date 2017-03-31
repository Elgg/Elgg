<?php

/**
 * Meta block body
 */
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

echo elgg_view_menu('meta_block', [
	'entity' => $entity,
	'sort_by' => 'priority',
	'class' => 'elgg-menu-page flex-column list-group list-group-flush',
	'item_class' => 'list-group-item',
]);

$menu = elgg()->menus->getMenu('entity', $vars)->getSection('default');

$items = [];
foreach ($menu as $item) {
	if ($item->getName() !== 'actions') {
		continue;
	}

	$children = $item->getChildren();
	foreach ($children as $child) {
		$child->setParentName(null);
		$child->setSection('action');
		$items[] = $child;
	}
}

if ($entity instanceof ElggUser) {
	$sections = elgg()->menus->getMenu('user_hover', $vars)->getSections();
	foreach ($sections as $section => $section_items) {
		if ($section == 'action') {
			continue;
		}
		foreach ($section_items as $item) {
			$items[] = $item;
		}
	}
}

echo elgg_view_menu('entity:actions', [
	'entity' => $entity,
	'items' => $items,
	'sort_by' => 'priority',
	'class' => 'elgg-menu-page flex-column list-group list-group-flush',
	'item_class' => 'list-group-item',
]);
