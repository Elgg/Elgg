<?php
/**
 * Elgg bookmark view
 *
 * @uses $vars['entity'] ElggBookmark to show
 */

$entity = elgg_extract('entity', $vars, false);
if (!$entity instanceof \ElggBookmark) {
	return;
}

if (elgg_extract('full_view', $vars)) {
	$link = elgg_view('output/url', [
		'href' => $entity->address,
		'icon' => 'thumbtack',
	]);
	
	$body = elgg_format_element('span', ['class' => ['elgg-heading-basic', 'mbs']], $link);
	$body .= elgg_view('output/longtext', [
		'value' => $entity->description,
		'class' => 'pbl',
	]);
	
	$body = elgg_format_element('div', ['class' => ['bookmark', 'elgg-content', 'mts']], $body);
	
	$params = [
		'icon' => true,
		'show_summary' => true,
		'body' => $body,
		'show_responses' => elgg_extract('show_responses', $vars, false),
		'show_navigation' => true,
	];
	$params = $params + $vars;

	echo elgg_view('object/elements/full', $params);
	return;
}

// brief view
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
