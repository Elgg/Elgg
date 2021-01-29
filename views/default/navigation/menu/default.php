<?php
/**
 * Default menu
 *
 * @uses $vars['name']                 Name of the menu
 * @uses $vars['menu']                 Array of menu items
 * @uses $vars['class']                Additional CSS class for the menu
 * @uses $vars['id']                   Menu id
 * @uses $vars['item_class']           Additional CSS class for each menu item
 * @uses $vars['show_section_headers'] Do we show headers for each section?
 */

use Elgg\Menu\MenuSection;
use Elgg\Menu\PreparedMenu;

$menu = elgg_extract('menu', $vars);
if (!$menu instanceof PreparedMenu) {
	return;
}

// we want css classes to use dashes
$name = elgg_extract('name', $vars, '');

$name_class_selector = preg_replace('/[^a-z0-9\-]/i', '-', elgg_strtolower($name));
		
$show_section_headers = elgg_extract('show_section_headers', $vars, false);
$item_class = elgg_extract_class($vars, [], 'item_class');

$class = elgg_extract_class($vars, ["elgg-menu", "elgg-menu-{$name_class_selector}"]);

$menu_view = '';

foreach ($menu as $section) {
	if (!$section instanceof MenuSection) {
		continue;
	}

	$section_class = $class;
	$section_class_selector = preg_replace('/[^a-z0-9\-]/i', '-', elgg_strtolower($section->getID()));
	$section_class[] = "elgg-menu-{$name_class_selector}-{$section_class_selector}";
	
	$menu_view .= elgg_view('navigation/menu/elements/section', [
		'items' => $section->all(),
		'class' => $section_class,
		'section' => $section->getID(),
		'name' => $name,
		'show_section_headers' => $show_section_headers,
		'item_class' => $item_class,
		'id' => elgg_extract('id', $vars),
	]);
}

if (!$menu_view) {
	return;
}

echo elgg_format_element('nav', [
	'class' => [
		'elgg-menu-container',
		"elgg-menu-{$name_class_selector}-container",
	],
	'data-menu-name' => $name,
], $menu_view);
