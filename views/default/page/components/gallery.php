<?php

/**
 * Gallery view
 *
 * Implemented as an unorder list
 *
 * @uses $vars['items']         Array of ElggEntity, ElggAnnotation or ElggRiverItem objects
 * @uses $vars['offset']        Index of the first list item in complete list
 * @uses $vars['limit']         Number of items per page
 * @uses $vars['count']         Number of items in the complete list
 * @uses $vars['pagination']    Show pagination? (default: true)
 * @uses $vars['position']      Position of the pagination: before, after, or both
 * @uses $vars['full_view']     Show the full view of the items (default: false)
 * @uses $vars['gallery_class'] Additional CSS class for the <ul> element
 * @uses $vars['item_class']    Additional CSS class for the <li> elements
 * @uses $vars['item_view']     Alternative view to render list items
 * @uses $vars['no_results']    Message to display if no results (string|Closure)
 */
$items = elgg_extract('items', $vars);
$pagination = elgg_extract('pagination', $vars, true);
$position = elgg_extract('position', $vars, 'after');
$no_results = elgg_extract('no_results', $vars, '');

if ($no_results === true) {
	$no_results = elgg_echo('notfound');
}

if (!$items && $no_results) {
	echo elgg_view('page/components/no_results', $vars);
	return;
}

if (!is_array($items) || count($items) == 0) {
	return;
}

elgg_push_context('gallery');

$list_classes = elgg_extract_class($vars, 'elgg-gallery', 'gallery_class');

$item_classes = elgg_extract_class($vars, 'elgg-item', 'item_class');

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

	$li_attrs = ['class' => $item_classes];

	if ($item instanceof \ElggEntity) {
		$li_attrs['id'] = "elgg-{$item->getType()}-{$item->getGUID()}";
	} else if (is_callable([$item, 'getType'])) {
		$li_attrs['id'] = "item-{$item->getType()}-{$item->id}";
	}

	$list_items .= elgg_format_element('li', $li_attrs, $item_view);
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

elgg_pop_context();
