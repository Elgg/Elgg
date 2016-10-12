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

$entity = elgg_extract('entity', $vars);

// we want css classes to use dashes
$vars['name'] = preg_replace('/[^a-z0-9\-]/i', '-', $vars['name']);
$headers = elgg_extract('show_section_headers', $vars, false);
$item_class = elgg_extract('item_class', $vars, '');

$class = elgg_extract_class($vars, ["elgg-menu", "elgg-menu-{$vars['name']}"]);

// Default section
$default = elgg_extract('default', $vars['menu']);
unset($vars['menu']['default']);

if (!empty($vars['menu'])) {
	$id = "elgg-popup-" . base_convert(mt_rand(), 10, 36);
	$link = elgg_view('output/url', [
		'rel' => 'popup',
		'text' => elgg_view_icon('ellipsis-v'),
		'href' => "#{$id}",
		'class' => 'elgg-popup-inline',
		'data-position' => json_encode([
			'my' => 'right top',
			'at' => 'center bottom',
			'collision' => 'fit fit',
			'within' => '.elgg-main',
		]),
	]);
		
	$popup = elgg_format_element('div', [
		'id' => $id,
		'class' => 'elgg-module-popup hidden',
	], elgg_view('navigation/menu/default', $vars));

	$default[] = ElggMenuItem::factory([
		'name' => 'ellipsis',
		'text' => $link . $popup,
		'href' => false,
	]);
}

echo elgg_view('navigation/menu/elements/section', array(
	'items' => $default,
	'class' => array_merge($class, ["elgg-menu-{$vars['name']}-$section"]),
	'section' => 'default',
	'name' => $vars['name'],
	'show_section_headers' => false,
	'item_class' => $item_class,
));