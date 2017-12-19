<?php
/**
 * Elgg bookmark view
 *
 * @package ElggBookmarks
 */

$full = elgg_extract('full_view', $vars, false);
$bookmark = elgg_extract('entity', $vars, false);

if (! $bookmark instanceof ElggObject) {
	return;
}

$owner = $bookmark->getOwnerEntity();
$owner_icon = elgg_view_entity_icon($owner, 'small');

$link = elgg_view('output/url', [
	'href' => $bookmark->address,
	'icon' => 'push-pin-alt',
]);

if (elgg_in_context('gallery')) {
	$owner_link = elgg_view('output/url', [
		'href' => $owner->getURL(),
		'text' => $owner->getDisplayName(),
	]);
	$date = elgg_view_friendly_time($bookmark->time_created);

	echo <<<HTML
<div class="bookmarks-gallery-item">
	<h3>{$bookmark->getDisplayName()}</h3>
	<p class='subtitle'>$owner_link $date</p>
</div>
HTML;

	return;
}

if ($full) {
	$description = elgg_view('output/longtext', ['value' => $bookmark->description, 'class' => 'pbl']);

	$params = [
		'title' => false,
		'handler' => 'bookmarks',
	];
	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

	$body = <<<HTML
<div class="bookmark elgg-content mts">
	<span class="elgg-heading-basic mbs">$link</span>
	$description
</div>
HTML;

	echo elgg_view('object/elements/full', [
		'entity' => $bookmark,
		'icon' => $owner_icon,
		'summary' => $summary,
		'body' => $body,
		'show_responses' => elgg_extract('show_responses', $vars, false),
		'show_navigation' => true,
	]);

	return;
}

// brief view
$url = $bookmark->address;
$display_text = $url;
$excerpt = elgg_get_excerpt($bookmark->description);
if ($excerpt) {
	$excerpt = " - $excerpt";
}

if (strlen($url) > 25) {
	$bits = parse_url($url);
	if (isset($bits['host'])) {
		$display_text = $bits['host'];
	} else {
		$display_text = elgg_get_excerpt($url, 100);
	}
}

$link = elgg_view('output/url', [
	'href' => $bookmark->address,
	'text' => $display_text,
	'icon' => 'push-pin-alt',
]);

$content = "$link{$excerpt}";

$params = [
	'content' => $content,
	'icon' => $owner_icon,
	'handler' => 'bookmarks',
];
$params = $params + $vars;
echo elgg_view('object/elements/summary', $params);
