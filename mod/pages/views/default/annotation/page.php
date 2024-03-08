<?php
/**
 * Revision view for history page
 */

$annotation = elgg_extract('annotation', $vars);
if (!$annotation instanceof \ElggAnnotation) {
	return;
}

$page = $annotation->getEntity();
if (!$page instanceof \ElggPage) {
	return;
}

$owner = $annotation->getOwnerEntity();
if (!$owner instanceof \ElggEntity) {
	return;
}

$params = [
	'title' => elgg_view_url($annotation->getURL(), $page->getDisplayName()),
	'byline' => true,
	'content' => false,
];
$params = $params + $vars;

echo elgg_view('annotation/elements/summary', $params);
