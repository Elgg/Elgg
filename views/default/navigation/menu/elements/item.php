<?php
/**
 * A single element of a menu.
 *
 * @package Elgg.Core
 * @subpackage Navigation
 */

$item = $vars['item'];

$link_class = 'elgg-menu-closed';
if ($item->getSelected()) {
	$item->setItemClass('elgg-state-selected');
	$link_class = 'elgg-menu-opened';
}

$children = $item->getChildren();
if ($children) {
	$item->setLinkClass($link_class);
	$item->setLinkClass('elgg-menu-parent');
}

$item_class = $item->getItemClass();

echo "<li class=\"$item_class\">";
echo $item->getContent();
if ($children) {
	echo elgg_view('navigation/menu/elements/section', array(
		'items' => $children,
		'class' => 'elgg-menu elgg-child-menu',
	));
}
echo '</li>';
