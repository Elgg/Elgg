<?php
/**
 * Menu group
 *
 * @uses $vars['items']
 * @uses $vars['class']
 * @uses $vars['section']
 */

$class = elgg_extract('class', $vars, '');
if (isset($vars['section'])) {
	$class = "$class elgg-section-{$vars['section']}";
}

echo "<ul class=\"$class\">";
foreach ($vars['items'] as $menu_item) {
	echo elgg_view('navigation/menu/elements/item', array('item' => $menu_item));
}
echo '</ul>';
