<?php

/**
 * View a list of items
 *
 * @package Elgg
 *
 * @uses $vars['items']          Array of ElggEntity, ElggAnnotation or ElggRiverItem objects
 * @uses $vars['offset']         Index of the first list item in complete list
 * @uses $vars['limit']          Number of items per page. Only used as input to pagination.
 * @uses $vars['count']          Number of items in the complete list
 * @uses $vars['base_url']       Base URL of list (optional)
 * @uses $vars['url_fragment']   URL fragment to add to links if not present in base_url (optional)
 * @uses $vars['pagination']     Show pagination? (default: true)
 * @uses $vars['position']       Position of the pagination: before, after, or both
 * @uses $vars['full_view']      Show the full view of the items (default: false)
 * @uses $vars['list_class']     Additional CSS class for the <ul> element
 * @uses $vars['item_class']     Additional CSS class for the <li> elements
 * @uses $vars['item_view']      Alternative view to render list items content
 * @uses $vars['list_item_view'] Alternative view to render list items
 * @uses $vars['no_results']     Message to display if no results (string|true|Closure)
 */
$items = elgg_extract('items', $vars);
$count = elgg_extract('count', $vars);
$pagination = elgg_extract('pagination', $vars, true);
$position = elgg_extract('position', $vars, 'after');
$no_results = elgg_extract('no_results', $vars, '');

if ($no_results === true) {
	$vars['no_results'] = elgg_echo('notfound');
}

if (!$items && $no_results) {
	echo elgg_view('page/components/no_results', $vars);
	return;
}

if (!is_array($items) || count($items) == 0) {
	return;
}

$list_classes = elgg_extract_class($vars, 'elgg-list', 'list_class');

$list_item_view = elgg_extract('list_item_view', $vars);
if (empty($list_item_view) || !elgg_view_exists($list_item_view)) {
	$list_item_view = 'page/components/list/item';
}

$nav = ($pagination) ? elgg_view('navigation/pagination', $vars) : '';

$index = 0;
$list_items = '';
foreach ($items as $item) {
	$item_view_vars = $vars;
	$item_view_vars['list_item_index'] = $index;
	$item_view = elgg_view_list_item($item, $item_view_vars);
	if (!$item_view) {
		continue;
	}

	$list_item_vars = $vars;
	$list_item_vars['content'] = $item_view;
	$list_item_vars['item'] = $item;
	
	$list_items .= elgg_view($list_item_view, $list_item_vars);
	
	$index++;
}

if ($position == 'before' || $position == 'both') {
	echo $nav;
}

if (empty($list_items) && $no_results) {
	// there are scenarios where item views do not output html. In those cases show the no results info
	echo elgg_view('page/components/no_results', $vars);
} else {
	echo elgg_format_element('ul', ['class' => $list_classes], $list_items);
}

if ($position == 'after' || $position == 'both') {
	echo $nav;
}
