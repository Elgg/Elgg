<?php
/**
 * View a list of entities
 *
 * @package Elgg
 *
 */

$context = $vars['context'];
$offset = $vars['offset'];
$entities = $vars['entities'];
$limit = $vars['limit'];
$count = $vars['count'];
$base_url = $vars['baseurl'];
$context = $vars['context'];
$list_type = $vars['listtype'];
$pagination = $vars['pagination'];
$full_view = $vars['fullview'];

$html = "";
$nav = "";

$list_type_toggle = elgg_get_array_value('listtypetoggle', $vars, true);

if ($context == "search" && $count > 0 && $list_type_toggle) {
	$nav .= elgg_view('navigation/listtype', array(
		'baseurl' => $base_url,
		'offset' => $offset,
		'count' => $count,
		'listtype' => $list_type,
	));
}

if ($pagination) {
	$nav .= elgg_view('navigation/pagination', array(
		'baseurl' => $base_url,
		'offset' => $offset,
		'count' => $count,
		'limit' => $limit,
	));
}

if ($list_type == 'list') {
	if (is_array($entities) && sizeof($entities) > 0) {
		$html .= '<ul class="elgg-entity-list elgg-list">';
		foreach ($entities as $entity) {
			$html .= '<li>';
			$html .= elgg_view_entity($entity, $full_view);
			$html .= '</li>';
		}
		$html .= '</ul>';
	}
} else {
	if (is_array($entities) && sizeof($entities) > 0) {
		$html .= elgg_view('entities/gallery', array('entities' => $entities));
	}
}

if ($count) {
	$html .= $nav;
}

echo $html;
