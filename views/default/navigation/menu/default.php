<?php
/**
 * Default menu
 *
 * @uses $vars['name']                 Name of the menu
 * @uses $vars['menu']                 Array of menu items
 * @uses $vars['class']                Additional CSS class for the menu
 * @uses $vars['item_class']           Additional CSS class for each menu item
 * @uses $vars['show_section_headers'] Do we show headers for each section?
 */

// we want css classes to use dashes
$name = elgg_extract('name', $vars, '');

$name_class_selector = preg_replace('/[^a-z0-9\-]/i', '-', strtolower($name));
		
$show_section_headers = elgg_extract('show_section_headers', $vars, false);
$item_class = elgg_extract('item_class', $vars, '');

$class = elgg_extract_class($vars, ["elgg-menu", "elgg-menu-{$name_class_selector}"]);

$menu_view = '';

foreach ($vars['menu'] as $section => $menu_items) {
	$section_class = $class;
	$section_class_selector = preg_replace('/[^a-z0-9\-]/i', '-', strtolower($section));
	$section_class[] = "elgg-menu-{$name_class_selector}-{$section_class_selector}";
	
	$menu_view .= elgg_view('navigation/menu/elements/section', [
		'items' => $menu_items,
		'class' => $section_class,
		'section' => $section,
		'name' => $name,
		'show_section_headers' => $show_section_headers,
		'item_class' => $item_class,
	]);
}

if ($menu_view) {
	echo elgg_format_element('nav', [
		'class' => [
			'elgg-menu-container',
			"elgg-menu-{$name_class_selector}-container",
			'clearfix',
		],
		'data-menu-name' => $name,
	], $menu_view);
}
