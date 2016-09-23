<?php

/**
 * Layout content filter
 *
 * @uses $vars['filter']      HTML for overriding the default filter (override),
 *                            or n array of tabs to register as filter menu items
 *                            (tab array elements should be compatible with elgg_register_menu_item())
 * @uses $vars['filter_vars'] Additional context variables to pass to the filter menu
 */

$tabs = [];

if (isset($vars['filter'])) {
	if (!is_array($vars['filter'])) {
		echo $vars['filter'];
		return;
	}

	$tabs = elgg_extract('filter', $vars);
}

$filter_vars = (array) elgg_extract('filter_vars', $vars, []);
$filter_vars['tabs'] = $tabs;

echo elgg_view('navigation/filter', $filter_vars);
