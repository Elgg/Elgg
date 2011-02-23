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
	echo elgg_view('navigation/menu/elements/group', array(
		'section' => $section, 
		'items' => $items,
		'class' => $class,
	));
}
