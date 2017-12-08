<?php

/**
 * Renders a filter menu
 *
 * @uses $vars['filter']       An optional array of filter tabs
 *                             Array items should be suitable for usage with
 *                             elgg_register_menu_item()
 * @uses $vars['filter_id']    An optional ID of the filter
 *                             If provided, plugins can adjust filter tabs menu
 *                             via 'register, filter:$filter_id' hook
 * @uses $vars['filter_value'] Optional name of the selected filter tab
 *                             If not provided, will be determined by current page's URL
 */

$filter_value = elgg_extract('filter_value', $vars);
$filter_tabs = (array) elgg_extract('filter', $vars, []);
foreach ($filter_tabs as $name => $tab) {
	if (!is_array($tab)) {
		continue;
	}
	if (!isset($tab['name'])) {
		$tab['name'] = $name;
	}
	if (!isset($tab['selected']) && $filter_value) {
		$tab['selected'] = $tab['name'] == $filter_value;
	}
	$filter_tabs[$name] = ElggMenuItem::factory($tab);
}

$filter_id = elgg_extract('filter_id', $vars);
$menu_name = $filter_id ? "filter:$filter_id" : 'filter';

$menu_params = $vars;
$menu_params['items'] = $filter_tabs;
$menu_params['sort_by'] = 'priority';
$menu_params['class'] = 'elgg-menu-filter';

echo elgg_view_menu($menu_name, $menu_params);
