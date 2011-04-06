<?php
/**
 * Body of river item
 *
 * @uses $vars['item']
 */

$item = $vars['item'];
$subject = $item->getSubjectEntity();

// river item header
$params = array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
);
$subject_link = elgg_view('output/url', $params);
$timestamp = elgg_get_friendly_time($item->getPostedTime());

$header = elgg_view_menu('river', array('item' => $item, 'sort_by' => 'priority'));
$header .= "$subject_link <span class=\"elgg-river-timestamp\">$timestamp</span>";

// body
$body = elgg_view($item->getView(), array('item' => $item));

// footer
$footer = elgg_view('river/elements/footer', $vars);

echo elgg_view('page/components/module', array(
	'header' => $header,
	'body' => $body,
	'footer' => $footer,
	'class' => 'mbn',
));