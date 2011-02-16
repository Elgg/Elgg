<?php
/**
 * Elgg bookmark view
 *
 * @package ElggBookmarks
 */

$full = elgg_extract('full', $vars, FALSE);
$bookmark = elgg_extract('entity', $vars, FALSE);

if (!$bookmark) {
	return;
}

$owner = $bookmark->getOwnerEntity();
$container = $bookmark->getContainerEntity();
$categories = elgg_view('categories/view', $vars);

$link = filter_tags(elgg_view('output/url', array('href' => $bookmark->address)));
$description = elgg_view('output/longtext', array('value' => $bookmark->description, 'class' => 'pbl'));

$owner_link = elgg_view('output/url', array(
	'href' => "pg/file/owner/$owner->username",
	'text' => $owner->name,
));
$author_text = elgg_echo('blog:author_by_line', array($owner_link));

$tags = elgg_view('output/tags', array('tags' => $bookmark->tags));
$date = elgg_view_friendly_time($bookmark->time_created);

$comments_count = $bookmark->countComments();
//only display if there are commments
if ($comments_count != 0) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', array(
		'href' => $bookmark->getURL() . '#comments',
		'text' => $text,
	));
} else {
	$comments_link = '';
}

$metadata = elgg_view('layout/objects/list/metadata', array(
	'entity' => $bookmark,
	'handler' => 'bookmarks',
));

$subtitle = "$author_text $date $categories $comments_link";

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}

if ($full && !elgg_in_context('gallery')) {
	$header = elgg_view_title($bookmark->title);

	$params = array(
		'entity' => $bookmark,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);
	$bookmark_info = elgg_view('layout/objects/list/body', $params);

	echo <<<HTML
$header
$bookmark_info
<div class="bookmark elgg-content">
	<span class="elgg-icon elgg-icon-following"></span><h3 class="pbl">$link</h3>
	$description
</div>
HTML;

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

	if (strlen($url) > 25) {
		$bits = parse_url($url);
		if (isset($bits['host'])) {
			$display_text = $bits['host'];
		} else {
			$display_text = elgg_get_excerpt($url, 100);
		}
	}

	$link = filter_tags(elgg_view('output/url', array(
		'href' => $bookmark->address,
		'text' => $display_text
	)));

	$content = "<span class=\"elgg-icon elgg-icon-following\"></span>$link - $excerpt";

	$params = array(
		'entity' => $bookmark,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
		'content' => $content,
	);

	echo elgg_view('layout/objects/list/body', $params);
}