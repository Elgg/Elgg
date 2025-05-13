<?php
/**
 * Elgg bookmark listing/summary view
 *
 * @uses $vars['entity'] ElggBookmark to show
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggBookmark) {
	return;
}

$url = $entity->address;
$display_text = $url;
$excerpt = elgg_get_excerpt((string) $entity->description);
if (!elgg_is_empty($excerpt)) {
	$excerpt = " - {$excerpt}";
}

if (elgg_strlen($url) > 25) {
	$bits = parse_url($url);
	$display_text = $bits['host'] ?? elgg_get_excerpt($url, 100);
}

$link = elgg_view('output/url', [
	'href' => $entity->address,
	'text' => $display_text,
	'icon' => 'thumbtack',
]);

$params = [
	'content' => $link . $excerpt,
	'icon' => true,
];
$params = $params + $vars;
echo elgg_view('object/elements/summary', $params);
