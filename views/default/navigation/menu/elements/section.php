<?php
/**
 * Menu group
 *
 * @uses $vars['items']
 * @uses $vars['class']
 * @uses $vars['name']
 * @uses $vars['section']
 * @uses $vars['show_section_headers']
 */

$headers = elgg_extract('show_section_headers', $vars, false);
$class = elgg_extract('class', $vars, '');

if ($headers) {
	$name = elgg_extract('name', $vars);
	$section = elgg_extract('section', $vars);
	echo '<h2>' . elgg_echo("menu:$name:header:$section") . '</h2>';
}

echo "<ul class=\"$class\">";
foreach ($vars['items'] as $menu_item) {
	echo elgg_view('navigation/menu/elements/item', array('item' => $menu_item));
}
echo '</ul>';
