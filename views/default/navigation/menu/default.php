<?php
/**
 * Default menu
 *
 * @uses $vars['menu']
 * @uses $vars['class']
 */

$class = 'elgg-menu';
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}

foreach ($vars['menu'] as $section => $menu_items) {
	echo "<ul class=\"$class\">";
	foreach ($menu_items as $menu_item) {
		echo elgg_view('navigation/menu/elements/item', array('item' => $menu_item));
	}
	echo '</ul>';
}
