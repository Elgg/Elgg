<?php
/**
 * Default menu
 *
 * @uses $vars['name']
 * @uses $vars['menu']
 * @uses $vars['class']
 * @uses $vars['show_section_headers']
 */

// we want css classes to use dashes
$vars['name'] = str_replace('_', '-', $vars['name']);
$headers = elgg_extract('show_section_headers', $vars, false);

$class = "elgg-menu elgg-menu-{$vars['name']}";
if (isset($vars['class'])) {
	$class .= " {$vars['class']}";
}

foreach ($vars['menu'] as $section => $menu_items) {
	echo elgg_view('navigation/menu/elements/section', array(
		'items' => $menu_items,
		'class' => "$class elgg-menu-{$vars['name']}-$section",
		'section' => $section,
		'name' => $vars['name'],
		'show_section_headers' => $headers
	));
}
