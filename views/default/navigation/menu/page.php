<?php
/**
 * Page menu
 *
 * @uses $vars['menu']
 * @uses $vars['selected_item']
 * @uses $vars['class']
 */

$class = 'elgg-menu elgg-menu-page';
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}

if (isset($vars['selected_item'])) {
	$parent = $vars['selected_item']->getParent();
	while ($parent) {
		$parent->setSelected();
		$parent = $parent->getParent();
	}
}

foreach ($vars['menu'] as $section => $menu_items) {
	echo elgg_view('navigation/menu/elements/group', array(
		'items' => $menu_items,
		'section' => $section,
		'class' => $class,
	));
}
