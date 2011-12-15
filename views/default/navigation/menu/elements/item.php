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

$item = $vars['item'];

$link_class = 'elgg-menu-closed';
if ($item->getSelected()) {
	$item->setItemClass('elgg-state-selected');
	$link_class = 'elgg-menu-opened';
}

$children = $item->getChildren();
if ($children) {
	$item->addLinkClass($link_class);
	$item->addLinkClass('elgg-menu-parent');
}

$item_class = $item->getItemClass();
if (isset($vars['item_class']) && $vars['item_class']) {
	$item_class .= ' ' . $vars['item_class'];
}

echo "<li class=\"$item_class\">";
echo $item->getContent();
if ($children) {
	echo elgg_view('navigation/menu/elements/section', array(
		'items' => $children,
		'class' => 'elgg-menu elgg-child-menu',
	));
}
echo '</li>';
