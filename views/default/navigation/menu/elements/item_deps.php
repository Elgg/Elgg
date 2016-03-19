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

foreach ($item->getDeps() as $module) {
	elgg_require_js($module);
}