<?php
/**
 * Elgg submenu group.  Writes the <ul> for a submenu and passes items one by one
 * to navigation/submenu_item
 *
 * @uses $vars['group_name']
 * @uses $vars['items']
 * @package Elgg
 * @subpackage Core
 */

$group = (isset($vars['group'])) ? $vars['group'] : 'default';
$items = (isset($vars['items'])) ? $vars['items'] : array();
$hidden = (isset($vars['hidden']) && $vars['hidden']) ? 'hidden' : '';
$child = (isset($vars['child']) && $vars['child']) ? 'child' : '';

echo "<ul class='submenu $group $hidden $child'>\n";

foreach ($items as $item) {
	$item_vars = array('item' => $item, 'group' => $group);
	if (isset($item->vars) && is_array($item->vars)) {
		$item_vars = array_merge($item->vars, $item_vars);
	}

	if (isset($item->children)) {
		$child_vars = array(
			'group' => $group,
			'items' => $item->children,
			// if this menu item is selected, make sure to display the full tree
			// ie, don't hide it.
			'hidden' => !$item->selected,
			'child' => TRUE
		);
		$item_vars['children_html'] = elgg_view('navigation/submenu_group', $child_vars);
	}

	echo elgg_view('navigation/submenu_item', $item_vars);
}

echo "</ul>\n";