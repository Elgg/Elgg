<?php
/**
 * Tab navigation
 *
 * @uses string $vars['type'] horizontal || vertical - Defaults to horizontal
 * @uses string $vars['class'] Additional class to add to ul
 * @uses array  $vars['tabs'] A multi-dimensional array of tab entries in the format for ElggMenuItem
 */

$tabs = elgg_extract('tabs', $vars);
if (empty($tabs) || !is_array($tabs)) {
	return;
}
unset($vars['tabs']);

// make sure tabs have a name (for menu item rendering)
foreach ($tabs as $index => &$tab) {
	if (isset($tab['name'])) {
		continue;
	}
	
	$tab['name'] = "tab-{$index}";
}

$options = $vars;

$type = elgg_extract('type', $vars, 'horizontal');
unset($options['type']);

$class = [
	'elgg-tabs',
];
if ($type == 'horizontal') {
	$class[] = 'elgg-htabs';
} else {
	$class[] = 'elgg-vtabs';
}

$options['class'] = elgg_extract_class($options, $class);
$options['items'] = $tabs;

echo elgg_view_menu('navigation/tabs', $options);
