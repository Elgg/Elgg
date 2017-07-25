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
$owner_icon = elgg_view_entity_icon($owner, 'tiny');
$categories = elgg_view('output/categories', $vars);

$link = elgg_view('output/url', ['href' => $bookmark->address]);
$description = elgg_view('output/longtext', ['value' => $bookmark->description, 'class' => 'pbl']);

$by_line = elgg_view('object/elements/imprint', $vars);

$comments_count = $bookmark->countComments();
//only display if there are commments
if ($comments_count != 0) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', [
		'href' => $bookmark->getURL() . '#comments',
		'text' => $text,
		'is_trusted' => true,
	]);
} else {
	$comments_link = '';
}

$subtitle = "$by_line $comments_link $categories";

$metadata = '';
if (!elgg_in_context('widgets') && !elgg_in_context('gallery')) {
	// only show entity menu outside of widgets and gallery view
	$metadata = elgg_view_menu('entity', [
		'entity' => $vars['entity'],
		'handler' => 'bookmarks',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	]);
}

if ($full && !elgg_in_context('gallery')) {
	$params = [
		'entity' => $bookmark,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
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

	$responses = '';
	if (elgg_extract('show_responses', $vars, false)) {
		$responses = elgg_view_comments($bookmark);
	}
	echo elgg_view('object/elements/full', [
		'entity' => $bookmark,
		'icon' => $owner_icon,
		'summary' => $summary,
		'body' => $body,
		'responses' => $responses,
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
		'entity' => $bookmark,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'content' => $content,
		'icon' => $owner_icon,
	];
	$params = $params + $vars;
	echo elgg_view('object/elements/summary', $params);
}
