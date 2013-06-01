<?php
/**
 * Gallery view
 *
 * Implemented as an unorder list
 *
 * @uses $vars['items']         Array of ElggEntity or ElggAnnotation objects
 * @uses $vars['offset']        Index of the first list item in complete list
 * @uses $vars['limit']         Number of items per page
 * @uses $vars['count']         Number of items in the complete list
 * @uses $vars['base_url']		Base URL of list (optional)
 * @uses $vars['pagination']    Show pagination? (default: true)
 * @uses $vars['position']      Position of the pagination: before, after, or both
 * @uses $vars['full_view']     Show the full view of the items (default: false)
 * @uses $vars['gallery_class'] Additional CSS class for the <ul> element
 * @uses $vars['item_class']    Additional CSS class for the <li> elements
 */

$items = $vars['items'];
if (!is_array($items) || sizeof($items) == 0) {
	return;
}

elgg_push_context('gallery');

$offset = $vars['offset'];
$limit = $vars['limit'];
$count = $vars['count'];
$pagination = elgg_extract('pagination', $vars, true);
$offset_key = elgg_extract('offset_key', $vars, 'offset');
$position = elgg_extract('position', $vars, 'after');

$gallery_class = 'elgg-gallery';
if (isset($vars['gallery_class'])) {
	$gallery_class = "$gallery_class {$vars['gallery_class']}";
}

$item_class = 'elgg-item';
if (isset($vars['item_class'])) {
	$item_class = "$item_class {$vars['item_class']}";
}

$nav = '';
if ($pagination && $count) {
	$nav .= elgg_view('navigation/pagination', array(
		'offset' => $offset,
		'count' => $count,
		'limit' => $limit,
		'offset_key' => $offset_key,
	));
}

if ($position == 'before' || $position == 'both') {
	echo $nav;
}

if (is_array($items) && count($items) > 0) {
	$html .= "<ul class=\"$gallery_class\">";
	foreach ($items as $item) {
		$li = elgg_view_list_item($item, $vars);
		if (!empty($li)) {
			if (elgg_instanceof($item)) {
				$id = "elgg-{$item->getType()}-{$item->getGUID()}";
			} else {
				$id = "item-{$item->getType()}-{$item->id}";
			}
			$html .= "<li id=\"$id\" class=\"$item_class\">$li</li>";
		}
	}
	$html .= '</ul>';
}

if ($position == 'after' || $position == 'both') {
	echo $nav;
}

elgg_pop_context();
