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
$items = $vars['items'];
$count = elgg_extract('count', $vars);
$pagination = elgg_extract('pagination', $vars, true);
$position = elgg_extract('position', $vars, 'after');
$no_results = elgg_extract('no_results', $vars, '');

if (!$items && $no_results) {
	if ($no_results instanceof Closure) {
		echo $no_results();
		return;
	}
	echo "<p class='elgg-no-results'>$no_results</p>";
	return;
}

if (!is_array($items) || count($items) == 0) {
	return;
}

elgg_push_context('gallery');

$list_classes = ['elgg-gallery'];
if (isset($vars['gallery_class'])) {
	$list_classes[] = $vars['gallery_class'];
}

$item_classes = ['elgg-item'];
if (isset($vars['item_class'])) {
	$item_classes[] = $vars['item_class'];
}

$nav = ($pagination) ? elgg_view('navigation/pagination', $vars) : '';

$list_items = '';
foreach ($items as $item) {
	$item_view = elgg_view_list_item($item, $vars);
	if (!$item_view) {
		continue;
	}

	$li_attrs = ['class' => $item_classes];

	if ($item instanceof \ElggEntity) {
		$li_attrs['id'] = "elgg-{$item->getType()}-{$item->getGUID()}";
	} else if (is_callable(array($item, 'getType'))) {
		$li_attrs['id'] = "item-{$item->getType()}-{$item->id}";
	}

	$list_items .= elgg_format_element('li', $li_attrs, $item_view);
}

if ($position == 'before' || $position == 'both') {
	echo $nav;
}

echo elgg_format_element('ul', ['class' => $list_classes], $list_items);

if ($position == 'after' || $position == 'both') {
	echo $nav;
}

elgg_pop_context();
