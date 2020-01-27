<?php
/**
 * Embed tabs
 *
 * @uses $vars['menu']['default']
 */

use Elgg\Collections\Collection;

$tabs = [];

$menu = elgg_extract('menu', $vars);
if (!$menu instanceof Collection) {
	return;
}

if (empty($menu->count())) {
	return;
}

foreach ($menu['default'] as $menu_item) {
	$tabs[] = [
		'text' => $menu_item->getText(),
		'href' => 'embed/' . $menu_item->getName(),
		'link_class' => 'embed-section',
		'selected' => $menu_item->getSelected(),
	];
}

echo elgg_view('navigation/tabs', ['tabs' => $tabs]);
