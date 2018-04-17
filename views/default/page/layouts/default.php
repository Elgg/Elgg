<?php

/**
 * Elgg default layout
 *
 * @uses $vars['layout_attrs'] Additional attributes to apply to the layout
 * @uses $vars['class']        Additional CSS classes to apply to the layout
 *
 * @uses $vars['breadcrumbs']  Breadcrumbs
 *                             Will no be rendered if the value is 'false'
 *                             Will render 'navigation/breadcrumbs' view if
 *                             not set or is an array of breadcrumbs
 *                             Will override breadcrumbs view if set to a string
 *
 * @uses $vars['title']        Optional title for main content area
 * @uses $vars['header']       Optional override for the header
 *
 * @uses $vars['content']      Page content

 * @uses $vars['footer']       Optional footer
 *
 * @uses $vars['sidebar']      Sidebar HTML (default: empty string)
 *                             Will not be rendered if the value is 'false'
 * @uses $vars['sidebar_alt']  Second sidebar HTML (default: false)
 *                             Will not be rendered if the value is 'false'
 *
 * @uses $vars['filter']       An optional array of filter tabs
 *                             Array items should be suitable for usage with
 *                             elgg_register_menu_item()
 * @uses $vars['filter_id']    An optional ID of the filter
 *                             If provided, plugins can adjust filter tabs menu
 *                             via 'register, menu:filter:$filter_id' hook
 * @uses $vars['filter_value'] Optional name of the selected filter tab
 *                             If not provided, will be determined by the current page's URL
 */

$layout_attrs = elgg_extract('layout_attrs', $vars, []);
unset($vars['layout_attrs']);

$class = elgg_extract_class($vars, [
	'elgg-layout',
	'clearfix'
]);
unset($vars['class']);

// Prepare layout sidebar
$vars['sidebar'] = elgg_extract('sidebar', $vars, '');
if ($vars['sidebar'] !== false) {
	// In a default layout, we want to make sure we render
	// sidebar navigation items
	$vars['sidebar'] = elgg_view('page/elements/sidebar', $vars);
}
$sidebar = elgg_view('page/layouts/elements/sidebar', $vars);

// Prepare second layout sidebar
$vars['sidebar_alt'] = elgg_extract('sidebar_alt', $vars, false, false);
if ($vars['sidebar_alt'] !== false) {
	// In a default layout, we want to make sure we render
	// sidebar navigation items
	$vars['sidebar_alt'] = elgg_view('page/elements/sidebar_alt', $vars);
}
$sidebar_alt = elgg_view('page/layouts/elements/sidebar_alt', $vars);

if ($sidebar && $sidebar_alt) {
	$class[] = 'elgg-layout-two-sidebar';
} else if ($sidebar) {
	$class[] = 'elgg-layout-one-sidebar';
} else {
	$class[] = 'elgg-layout-one-column';
}

$layout_attrs['class'] = elgg_extract_class($layout_attrs, $class);

$breadcrumbs = elgg_view('page/layouts/elements/breadcrumbs', $vars);
$header = elgg_view('page/layouts/elements/header', $vars);
$body = elgg_view('page/layouts/elements/body', $vars);

$layout = $breadcrumbs;
$layout .= $header;
$layout .= elgg_format_element('div', [
	'class' => 'elgg-layout-columns',
], $sidebar_alt . $body . $sidebar);

echo elgg_format_element('div', $layout_attrs, $layout);
