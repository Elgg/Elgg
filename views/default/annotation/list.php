<?php
/**
 * Annotation list
 *
 * @uses $vars['annotations']
 * @uses $vars['limit']
 * @uses $vars['offset']
 * @uses $vars['count']
 * @uses $vars['pagination']
 */

$offset = $vars['offset'];
$limit = $vars['limit'];
$count = $vars['count'];
$annotations = $vars['annotations'];
$pagination = elgg_get_array_value('pagination', $vars, true);

$html = "";
$nav = "";

if ($pagination) {
	$nav .= elgg_view('navigation/pagination', array(
		'baseurl' => $_SERVER['REQUEST_URI'],
		'offset' => $offset,
		'count' => $count,
		'limit' => $limit,
		'word' => 'annoff',
		'nonefound' => false,
	));
}

if (is_array($annotations) && count($annotations) > 0) {
	$html .= '<ul class="elgg-annotation-list elgg-list">';
	foreach ($annotations as $annotation) {
			$html .= '<li>';
			$html .= elgg_view_annotation($annotation, true);
			$html .= '</li>';
	}
	$html .= '</ul>';
}

if ($count) {
	$html .= $nav;
}

echo $html;
