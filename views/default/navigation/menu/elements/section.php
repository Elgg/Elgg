<?php
/**
 * Menu group
 *
 * @uses $vars['items']                   Array of menu items
 * @uses $vars['class']                   Additional CSS class for the section
 * @uses $vars['name']                    Name of the menu
 * @uses $vars['section']                 The section name
 * @uses $vars['item_class']              Additional CSS class for each menu item
 * @uses $vars['show_section_headers']    Do we show headers for each section
 * @uses $vars['section_header_tag_name'] (optional) tag to be used for the section header
 */

$items = elgg_extract('items', $vars, []);
if (empty($items) || !is_array($items)) {
	return;
}

unset($vars['items']);

$headers = elgg_extract('show_section_headers', $vars, false);
unset($vars['show_section_headers']);

$item_class = elgg_extract_class($vars, [], 'item_class');
unset($vars['item_class']);

$name = elgg_extract('name', $vars);
unset($vars['name']);

$section = elgg_extract('section', $vars);
unset($vars['section']);
$vars['data-menu-section'] = $section;

if ($headers) {
	echo elgg_format_element(elgg_extract('section_header_tag_name', $vars, 'div'), [
		'class' => 'elgg-menu-section-header',
	], elgg_echo("menu:$name:header:$section"));
}

$lis = [];
foreach ($items as $menu_item) {
	$item = elgg_view('navigation/menu/elements/item', [
		'item' => $menu_item,
		'item_class' => $item_class,
	]);
	
	if (empty($item)) {
		continue;
	}
	
	$lis[] = $item;
}

if (empty($lis)) {
	return;
}

echo elgg_format_element('ul', $vars, implode(PHP_EOL, $lis));
