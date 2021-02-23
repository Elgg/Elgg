<?php
/**
 * Elgg bookmark view
 *
 * @uses $vars['entity'] ElggBookmark to show
 */

$entity = elgg_extract('entity', $vars, false);
if (!$entity instanceof ElggBookmark) {
	return;
}

$owner = $entity->getOwnerEntity();

$link = elgg_view('output/url', [
	'href' => $entity->address,
	'icon' => 'thumbtack',
]);

if (elgg_in_context('gallery')) {
	$owner_link = elgg_view_entity_url($owner);
	$date = elgg_view_friendly_time($entity->time_created);

	echo <<<HTML
<div class="bookmarks-gallery-item">
	<h3>{$entity->getDisplayName()}</h3>
	<p class='subtitle'>$owner_link $date</p>
</div>
HTML;

	return;
}

if (elgg_extract('full_view', $vars)) {
	$description = elgg_view('output/longtext', [
		'value' => $entity->description,
		'class' => 'pbl',
	]);

	$body = <<<HTML
<div class="bookmark elgg-content mts">
	<span class="elgg-heading-basic mbs">$link</span>
	$description
</div>
HTML;
	
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
$excerpt = elgg_get_excerpt($entity->description);
if ($excerpt) {
	$excerpt = " - $excerpt";
}

if (elgg_strlen($url) > 25) {
	$bits = parse_url($url);
	if (isset($bits['host'])) {
		$display_text = $bits['host'];
	} else {
		$display_text = elgg_get_excerpt($url, 100);
	}
}

$link = elgg_view('output/url', [
	'href' => $entity->address,
	'text' => $display_text,
	'icon' => 'thumbtack',
]);

$content = "$link{$excerpt}";

$params = [
	'content' => $content,
	'icon' => true,
];
$params = $params + $vars;
echo elgg_view('object/elements/summary', $params);
