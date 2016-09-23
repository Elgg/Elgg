<?php
/**
 * All groups listing page navigation
 *
 * @uses $vars['selected'] Name of the tab that has been selected
 * @deprecated 2.3
 */

elgg_deprecated_notice("'groups/group_sort_menu' view has been deprecated. "
		. "Use elgg_get_filter_tabs() as the layout filter value", '2.3');

$tabs = elgg_get_filter_tabs('groups/all', $vars['selected']);

foreach ($tabs as $name => $tab) {
	$tab['name'] = $name;
	elgg_register_menu_item('filter', $tab);
}

echo elgg_view_menu('filter', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz'));
