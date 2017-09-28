<?php

/**
 * Load AMD modules required by the menu item
 *
 * @package Elgg.Core
 * @subpackage Navigation
 *
 * @uses $vars['item'] ElggMenuItem
 */

$item = elgg_extract('item', $vars);
if (!$item instanceof ElggMenuItem) {
	return;
}

$deps = $item->getDeps();

if (elgg_extract('data-toggle', $item->getValues())) {
	$deps[] = 'navigation/menu/elements/item_toggle';
}

foreach ($deps as $module) {
	elgg_require_js($module);
}
