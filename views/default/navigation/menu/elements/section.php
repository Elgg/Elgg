<?php
/**
 * Menu group
 *
 * @uses $vars['items']                Array of menu items
 * @uses $vars['class']                Additional CSS class for the section
 * @uses $vars['name']                 Name of the menu
 * @uses $vars['section']              The section name
 * @uses $vars['item_class']           Additional CSS class for each menu item
 * @uses $vars['show_section_headers'] Do we show headers for each section
 */

$items = elgg_extract('items', $vars, array());
$headers = elgg_extract('show_section_headers', $vars, false);
$attrs['class'] = elgg_extract_class($vars);
$item_class = elgg_extract('item_class', $vars, '');

if ($headers) {
	$name = elgg_extract('name', $vars);
	$section = elgg_extract('section', $vars);
	echo '<h2>' . elgg_echo("menu:$name:header:$section") . '</h2>';
}

$lis = '';

if (is_array($items)) {
	foreach ($items as $menu_item) {
		$lis .= elgg_view('navigation/menu/elements/item', array(
			'item' => $menu_item,
			'item_class' => $item_class,
		));
	}
}

echo elgg_format_element('ul', $attrs, $lis);
