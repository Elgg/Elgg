<?php
/**
 * Menu group
 *
 * @uses $vars['items']
 * @uses $vars['class']
 */

$class = elgg_extract('class', $vars, '');

echo "<ul class=\"$class\">";
foreach ($vars['items'] as $menu_item) {
	echo elgg_view('navigation/menu/elements/item', array('item' => $menu_item));
}
echo '</ul>';
