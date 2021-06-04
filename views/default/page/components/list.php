<?php
/**
 * View a list of items
 *
 * For options to influence the pagination also look at the view 'navigation/pagination'
 *
 * @uses $vars['items']                     Array of ElggEntity, ElggAnnotation or ElggRiverItem objects
 * @uses $vars['pagination']                Show pagination? (default: true)
 * @uses $vars['pagination_after_options']  Specific options for the pagination view to be used when the pagination is shown after the item list
 * @uses $vars['pagination_before_options'] Specific options for the pagination view to be used when the pagination is shown before the item list
 * @uses $vars['position']                  Position of the pagination: before, after, or both
 * @uses $vars['list_class']                Additional CSS class for the <ul> element
 * @uses $vars['item_class']                Additional CSS class for the <li> elements
 * @uses $vars['item_view']                 Alternative view to render list items content
 * @uses $vars['list_item_view']            Alternative view to render list items
 * @uses $vars['no_results']                Message to display if no results (string|true|Closure)
 */

$items = elgg_extract('items', $vars);

$no_results = elgg_extract('no_results', $vars, '');
if ($no_results === true) {
	$vars['no_results'] = elgg_echo('notfound');
}

if (!$items && $no_results) {
	echo elgg_view('page/components/no_results', $vars);
	echo elgg_view('page/components/list/out_of_bounds', $vars);
	return;
}

if (!is_array($items) || count($items) == 0) {
	return;
}

$position = elgg_extract('position', $vars, 'after');
$pagination = (bool) elgg_extract('pagination', $vars, true);
if (elgg_in_context('widget')) {
	// widgets do not show pagination
	$pagination = false;
}

$pagination_before_options = (array) elgg_extract('pagination_before_options', $vars, []);
unset($vars['pagination_before_options']);
$pagination_after_options = (array) elgg_extract('pagination_after_options', $vars, []);
unset($vars['pagination_after_options']);

$list_classes = elgg_extract_class($vars, 'elgg-list', 'list_class');

$list_item_view = elgg_extract('list_item_view', $vars);
if (empty($list_item_view) || !elgg_view_exists($list_item_view)) {
	$list_item_view = 'page/components/list/item';
}

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

$result = '';
if ($pagination && ($position == 'before' || $position == 'both')) {
	$pagination_options = array_merge($vars, $pagination_before_options);
	$pagination_options['position'] = 'before';
	
	$result .= elgg_view('navigation/pagination', $pagination_options);
}

if (empty($list_items) && $no_results) {
	// there are scenarios where item views do not output html. In those cases show the no results info
	$result .= elgg_view('page/components/no_results', $vars);
} else {
	$result .= elgg_format_element('ul', ['class' => $list_classes], $list_items);
}

if ($pagination && ($position == 'after' || $position == 'both')) {
	$pagination_options = array_merge($vars, $pagination_after_options);
	$pagination_options['position'] = 'after';
	
	$result .= elgg_view('navigation/pagination', $pagination_options);
}

$id = elgg_build_hmac([
	$list_classes,
	$list_item_view,
	elgg_extract('item_view', $vars),
	elgg_extract('type', $vars),
	elgg_extract('subtype', $vars),
	elgg_extract('offset_key', $vars),
	elgg_extract('pagination_class', $vars),
	elgg_extract('base_url', $vars),
])->getToken();

$container_classes = ['elgg-list-container'];
$pagination_behaviour = elgg_extract('pagination_behaviour', $vars, elgg_get_config('pagination_behaviour'));
if ($pagination && ($pagination_behaviour !== 'navigate')) {
	$container_classes[] = "elgg-list-container-{$pagination_behaviour}";
	elgg_require_js('page/components/list');
}

echo elgg_format_element('div', ['class' => $container_classes, 'id' => "list-container-{$id}"], $result);
