<?php
/**
 * A single element of a menu.
 *
 * @uses $vars['item']       ElggMenuItem
 * @uses $vars['item_class'] Additional CSS class for the menu item
 */

$item = elgg_extract('item', $vars);
if (!$item instanceof ElggMenuItem) {
	return;
}

$item_vars = [];
$child_menu_view = '';

$children = $item->getChildren();
if (!empty($children)) {
	$link_class = 'elgg-menu-closed';
	if ($item->getSelected()) {
		$link_class = 'elgg-menu-opened';
	}
	$item->addLinkClass($link_class);

	$item->addLinkClass('elgg-menu-parent');

	$child_menu_vars = $item->getChildMenuOptions();
	$child_menu_vars['items'] = $children;
	$child_menu_vars['class'] = elgg_extract_class($child_menu_vars, ['elgg-menu', 'elgg-child-menu']);

	$display = elgg_extract('display', $child_menu_vars, 'default');
	unset($child_menu_vars['display']);

	switch ($display) {
		case 'dropdown' :
			$item->addDeps(['elgg/menus/dropdown']);
			$item->addItemClass('elgg-menu-item-has-dropdown');
			break;

		case 'toggle' :
			$item->addDeps(['elgg/menus/toggle']);
			$item->addItemClass('elgg-menu-item-has-toggle');
			break;
	}

	$child_menu_view = elgg_view('navigation/menu/elements/section', $child_menu_vars);
} elseif ($item->getData('show_with_empty_children') === false) {
	return;
}

$deps = $item->getDeps();
if (elgg_extract('data-toggle', $item->getValues())) {
	$deps[] = 'navigation/menu/elements/item_toggle';
}

foreach ($deps as $module) {
	elgg_require_js($module);
}

$item_vars['data-menu-item'] = $item->getName();

$item_vars['class'] = elgg_extract_class($vars, $item->getItemClass(), 'item_class');
if ($item->getSelected()) {
	$item_vars['class'][] = 'elgg-state-selected';
}

$item_view = elgg_view($item->getItemContentsView() ?: 'navigation/menu/elements/item/url', [
	'item' => $item,
]);

$item_view .= $child_menu_view;

echo elgg_format_element('li', $item_vars, $item_view);
