<?php
/**
 * View for page object
 *
 * @package ElggPages
 */


$full = elgg_get_array_value('full', $vars, FALSE);
$page = elgg_get_array_value('entity', $vars, FALSE);

if (!$page) {
	return TRUE;
}

$owner = $page->getOwnerEntity();
$owner_icon = elgg_view('profile/icon', array('entity' => $owner, 'size' => 'tiny'));
$owner_link = elgg_view('output/url', array(
	'href' => "pg/pages/owner/$owner->username",
	'text' => $owner->name,
));
$author_text = elgg_echo('blog:author_by_line', array($owner_link));
$tags = elgg_view('output/tags', array('tags' => $page->tags));
$date = elgg_view_friendly_time($page->time_created);
$excerpt = elgg_get_excerpt($page->description);

$comments_count = elgg_count_comments($page);
//only display if there are commments
if ($comments_count != 0) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', array(
		'href' => $page->getURL() . '#page-comments',
		'text' => $text,
	));
} else {
	$comments_link = '';
}

$metadata = elgg_view('layout/objects/list/metadata', array(
	'entity' => $page,
	'handler' => 'pages',
));

$subtitle = "$author_text $date $categories $comments_link";

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}

if ($full) {
	$body = elgg_view('output/longtext', array('value' => $page->description));

	$params = array(
		'entity' => $page,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);
	$list_body = elgg_view('layout/objects/list/body', $params);

	$info = elgg_view_image_block($owner_icon, $list_body);

	echo <<<HTML
$info
$body
HTML;

} else {
	// brief view

	$params = array(
		'entity' => $page,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
		'content' => $excerpt,
	);
	$list_body = elgg_view('layout/objects/list/body', $params);

	echo elgg_view_image_block($owner_icon, $list_body);
}
