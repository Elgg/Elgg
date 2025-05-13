<?php
/**
 * Elgg bookmark full view
 *
 * @uses $vars['entity'] ElggBookmark to show
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggBookmark) {
	return;
}

$body = elgg_view('output/url', [
	'href' => $entity->address,
	'icon' => 'thumbtack',
]);

$body .= elgg_view('output/longtext', [
	'value' => $entity->description,
]);

$params = [
	'body' => $body,
	'icon' => true,
	'show_navigation' => true,
	'show_summary' => true,
];
$params = $params + $vars;
echo elgg_view('object/elements/full', $params);
