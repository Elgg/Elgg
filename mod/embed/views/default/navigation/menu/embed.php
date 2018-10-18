<?php
/**
 * Embed tabs
 *
 * @uses $vars['menu']['default']
 */

$tabs = [];
foreach ($vars['menu']['default'] as $menu_item) {
	$tabs[] = [
		'text' => $menu_item->getText(),
		'href' => 'embed/' . $menu_item->getName(),
		'link_class' => 'embed-section',
		'selected' => $menu_item->getSelected(),
	];
}

echo elgg_view('navigation/tabs', ['tabs' => $tabs]);
