<?php
/**
 * View for blog objects
 *
 * @package Blog
 */

$full = elgg_extract('full_view', $vars, false);
$blog = elgg_extract('entity', $vars, false);

if (!($blog instanceof \ElggBlog)) {
	return;
}

$owner = $blog->getOwnerEntity();
$owner_icon = elgg_view_entity_icon($owner, 'small');

if ($full) {
	$body = elgg_view('output/longtext', [
		'value' => $blog->description,
		'class' => 'blog-post',
	]);

	$params = [
		'entity' => $blog,
		'title' => false,
		'handler' => 'blog',
	];
	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

	echo elgg_view('object/elements/full', [
		'entity' => $blog,
		'summary' => $summary,
		'icon' => $owner_icon,
		'body' => $body,
		'show_navigation' => true,
	]);
} else {
	// brief view
	$excerpt = $blog->excerpt;
	if (!$excerpt) {
		$excerpt = elgg_get_excerpt($blog->description);
	}

	$params = [
		'entity' => $blog,
		'handler' => 'blog',
		'content' => $excerpt,
		'icon' => $owner_icon,
	];
	$params = $params + $vars;
	echo elgg_view('object/elements/summary', $params);
}
