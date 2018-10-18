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
	
	// BC tab params
	if (!isset($tab['text']) && isset($tab['title'])) {
		elgg_deprecated_notice("Passing 'title' instead of 'text' to the navigation/tabs tab is deprecated. Use 'text' instead.", '3.0');
		
		$tab['text'] = $tab['title'];
		unset($tab['title']);
	}

	if (!isset($tab['href']) && isset($tab['url'])) {
		elgg_deprecated_notice("Passing 'url' to the navigation/tabs tab is deprecated. Use 'href' instead.", '3.0');
		$tab['href'] = $tab['url'];
		unset($tab['url']);
	}

	if (isset($tab['id']) && isset($tab['link_id'])) {
		elgg_deprecated_notice("The navigation/tabs tab does not support passing 2 ids. Only 'id' is used to set the id of the menu item", '3.0');
	}
	
	if (!isset($tab['id']) && isset($tab['link_id'])) {
		elgg_deprecated_notice("Passing 'link_id' to the navigation/tabs tab is deprecated. Use 'id' instead.", '3.0');
		
		$tab['id'] = $tab['link_id'];
		unset($tab['link_id']);
	}

	if (!isset($tab['item_class']) && isset($tab['class'])) {
		elgg_deprecated_notice("Passing 'class' to the navigation/tabs tab is deprecated. Use 'item_class' instead.", '3.0');
		
		$tab['item_class'] = $tab['class'];
		unset($tab['class']);
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
