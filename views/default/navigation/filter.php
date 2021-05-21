<?php
/**
 * Renders a filter menu
 *
 * @uses $vars['filter']       An optional array of filter tabs
 *                             Array items should be suitable for usage with
 *                             elgg_register_menu_item()
 * @uses $vars['filter_id']    An optional ID of the filter
 *                             If provided, plugins can adjust filter tabs menu
 *                             via 'register, filter:$filter_id' hook (default: filter)
 * @uses $vars['filter_value'] Optional name of the selected filter tab
 *                             If not provided, will be determined by current page's URL
 */

$filter_tabs = (array) elgg_extract('filter', $vars, []);
foreach ($filter_tabs as $name => $tab) {
	if (!is_array($tab)) {
		continue;
	}
	if (!isset($tab['name'])) {
		$tab['name'] = $name;
	}
	$filter_tabs[$name] = ElggMenuItem::factory($tab);
}

$filter_id = elgg_extract('filter_id', $vars, 'filter');

$menu_params = $vars;
$menu_params['filter_id'] = $filter_id;
$menu_params['items'] = $filter_tabs;
$menu_params['sort_by'] = 'priority';
$menu_params['class'] = 'elgg-menu-filter';
$menu_params['selected_item_name'] = elgg_extract('selected_item_name', $vars, elgg_extract('filter_value', $vars)); // for BC

echo elgg_view_menu("filter:{$filter_id}", $menu_params);
