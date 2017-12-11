<?php
/**
 * Elgg bookmark view
 *
 * @package ElggBookmarks
 */

$full = elgg_extract('full_view', $vars, false);
$bookmark = elgg_extract('entity', $vars, false);

if (!$bookmark) {
	return;
}

$owner = $bookmark->getOwnerEntity();
$owner_icon = elgg_view_entity_icon($owner, 'small');

$link = elgg_view('output/url', ['href' => $bookmark->address]);

if ($full && !elgg_in_context('gallery')) {
	$description = elgg_view('output/longtext', ['value' => $bookmark->description, 'class' => 'pbl']);

	$params = [
		'title' => false,
		'handler' => 'bookmarks',
	];
	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

	$bookmark_icon = elgg_view_icon('push-pin-alt');
	$body = <<<HTML
<div class="bookmark elgg-content mts">
	$bookmark_icon<span class="elgg-heading-basic mbs">$link</span>
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
} elseif (elgg_in_context('gallery')) {
	echo <<<HTML
<div class="bookmarks-gallery-item">
	<h3>$bookmark->title</h3>
	<p class='subtitle'>$owner_link $date</p>
</div>
HTML;
} else {
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
	]);

	$content = elgg_view_icon('push-pin-alt') . "$link{$excerpt}";

	$params = [
		'content' => $content,
		'icon' => $owner_icon,
		'handler' => 'bookmarks',
	];
	$params = $params + $vars;
	echo elgg_view('object/elements/summary', $params);
}
