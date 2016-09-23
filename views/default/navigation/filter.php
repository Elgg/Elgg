<?php

/**
 * Renders a filter menu from an array of tabs
 *
 * @uses $vars['tabs']         An array of elements suitable for elgg_register_menu_item()
 *                             <code>
 *                               [
 *                                 'tab_name' => [
 *                                   'text' => 'Tab',
 *                                   'href' => '/path/to/page',
 *                                   'priority' => 200,
 *                                 ]
 *                               ]
 *                             </code>
 * @uses $vars['selected_tab'] Selected tab name
 * @uses $vars['class']        Additional menu classes
 * @uses $vars['sort_by']      Menu sorting
 * @uses $vars['menu_name']    Menu name (default: 'filter')
 */

$class = ['elgg-menu-hz'];

$menu_name = elgg_extract('menu_name', $vars, 'filter', false);
unset($vars['menu_name']);
if ($menu_name != 'filter') {
	$class[] = 'elgg-menu-filter';
}

$vars['class'] = elgg_extract_class($vars, $class);

if (!isset($vars['sort_by'])) {
	$vars['sort_by'] = 'priority';
}

$selected_tab = elgg_extract('selected_tab', $vars);
unset($vars['selected_tab']);

$tabs = (array) elgg_extract('tabs', $vars, []);
foreach ($tabs as $name => $tab) {
	if (!isset($tab['name'])) {
		$tab['name'] = $name;
	}
	if (!isset($tab['selected']) && $selected_tab) {
		$tab['selected'] = $name == $selected_tab;
	}
	$tabs[$name] = $tab;
}

$vars['items'] = $tabs;
echo elgg_view_menu($menu_name, $vars);

