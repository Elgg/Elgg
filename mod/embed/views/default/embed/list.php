<?php
/**
 * View a list of embeddable items
 *
 * @package Elgg
 *
 * @uses $vars['items']       Array of ElggEntity objects
 * @uses $vars['offset']      Index of the first list item in complete list
 * @uses $vars['limit']       Number of items per page
 * @uses $vars['count']       Number of items in the complete list
 *
 * @uses $vars['list_class']  Additional CSS class for the <ul> element
 * @uses $vars['item_class']  Additional CSS class for the <li> elements
 */

$items = $vars['items'];
$offset = $vars['offset'];
$limit = $vars['limit'];
$count = $vars['count'];

$offset_key = elgg_extract('offset_key', $vars, 'offset');

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

if ($count) {
	$nav .= elgg_view('navigation/pagination', array(
		'offset' => $offset,
		'count' => $count,
		'limit' => $limit,
		'offset_key' => $offset_key,
	));
}

if (is_array($items) && count($items) > 0) {
	$html .= "<ul class=\"$list_class\">";
	foreach ($items as $item) {
		$id = "elgg-{$item->getType()}-{$item->getGUID()}";
		$html .= "<li id=\"$id\" class=\"$item_class\">";
		$html .= elgg_view('embed/item', array('entity' => $item));
		$html .= '</li>';
	}
	$html .= '</ul>';
}

$html .= $nav;

echo $html;
