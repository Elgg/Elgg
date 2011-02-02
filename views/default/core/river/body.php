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
$header = "$subject_link <span class=\"elgg-river-timestamp\">$timestamp</span>";

// body
$body = elgg_view($item->getView(), array('item' => $item));

// footer
$footer = elgg_view('core/river/footer', $vars);

$params = array(
	'header' => $header,
	'body' => $body,
	'footer' => $footer,
	'show_inner' => false,
);
echo elgg_view('layout/objects/module', $params);
