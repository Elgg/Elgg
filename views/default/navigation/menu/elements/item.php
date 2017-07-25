<?php
/**
 * A single element of a menu.
 *
 * @package Elgg.Core
 * @subpackage Navigation
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
}

$item_vars['data-menu-item'] = $item->getName();

$item_vars['class'][] = $item->getItemClass();
if ($item->getSelected()) {
	$item_vars['class'][] = "elgg-state-selected";
}
if (!empty($vars['item_class'])) {
	$item_vars['class'][] = $vars['item_class'];
}

$item_view = elgg_view_menu_item($item) . $child_menu_view;

echo elgg_format_element('li', $item_vars, $item_view);
