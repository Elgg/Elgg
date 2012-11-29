<?php
/**
 * View a list of items
 *
 * @package Elgg
 *
 * @uses $vars['items']       Array of ElggEntity or ElggAnnotation objects
 * @uses $vars['offset']      Index of the first list item in complete list
 * @uses $vars['limit']       Number of items per page. Only used as input to pagination.
 * @uses $vars['count']       Number of items in the complete list
 * @uses $vars['base_url']    Base URL of list (optional)
 * @uses $vars['pagination']  Show pagination? (default: true)
 * @uses $vars['position']    Position of the pagination: before, after, or both
 * @uses $vars['full_view']   Show the full view of the items (default: false)
 * @uses $vars['list_class']  Additional CSS class for the <ul> element
 * @uses $vars['item_class']  Additional CSS class for the <li> elements
 */

$items = $vars['items'];
$offset = elgg_extract('offset', $vars);
$limit = elgg_extract('limit', $vars);
$count = elgg_extract('count', $vars);
$base_url = elgg_extract('base_url', $vars, '');
$pagination = elgg_extract('pagination', $vars, true);
$offset_key = elgg_extract('offset_key', $vars, 'offset');
$position = elgg_extract('position', $vars, 'after');
$context = elgg_get_context();

$list_class = 'elgg-list';
if (isset($vars['list_class'])) {
	$list_class = "$list_class {$vars['list_class']}";
}

$item_class = 'elgg-item';
if (isset($vars['item_class'])) {
	$item_class = "$item_class {$vars['item_class']}";
}

$html = "";
$nav = "";

if ($pagination && $count) {
	$nav .= elgg_view('navigation/pagination', array(
		'base_url' => $base_url,
		'offset' => $offset,
		'count' => $count,
		'limit' => $limit,
		'offset_key' => $offset_key,
	));
}

if (is_array($items) && count($items) > 0) {
	$html .= "<ul class=\"$list_class\">";
	foreach ($items as $item) {
		if (elgg_instanceof($item)) {
			$id = "elgg-{$item->getType()}-{$item->getGUID()}";
		} else {
			$id = "item-{$item->getType()}-{$item->id}";
		}
		$html .= "<li id=\"$id\" class=\"$item_class\">";
		$html .= elgg_view_list_item($item, $vars);
		$html .= '</li>';
	}
	$html .= '</ul>';
}

if ($position == 'before' || $position == 'both') {
    $html = $nav . $html;
}
if (($count > 0) && ($context != 'widgets') && ($context != 'gallery')){
	$from_count = $offset + 1;
	$to_count = ' to ';
	if ($from_count < $count){
		if (($count - $from_count) < $limit ){
			$to_count .= $count;
		}
		else {
			$to_count .= ($offset + $limit);	
		}
	}
	else {
		$to_count = '';
	}
	$html .= '<div class="list-count">displaying: ' . $from_count . $to_count . ' of ' . $count . '</div>';
}

if ($position == 'after' || $position == 'both') {
    $html .= $nav;
}

echo $html;
