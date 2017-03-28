<?php
/**
 * View for blog objects
 *
 * @package Blog
 */

$full = elgg_extract('full_view', $vars, false);
$blog = elgg_extract('entity', $vars, false);

if (!$blog) {
	return;
}

$owner = $blog->getOwnerEntity();

$excerpt = $blog->excerpt;
if (!$excerpt) {
	$excerpt = elgg_get_excerpt($blog->description);
}

$owner_icon = elgg_view_entity_icon($owner, 'small');

// The "on" status changes for comments, so best to check for !Off
if ($blog->comments_on != 'Off') {
	$comments_count = $blog->countComments();
	//only display if there are commments
	if ($comments_count != 0) {
		$text = elgg_echo("comments") . " ($comments_count)";
		$comments_link = elgg_view('output/url', [
			'href' => $blog->getURL() . '#comments',
			'text' => $text,
			'is_trusted' => true,
		]);
	} else {
		$comments_link = '';
	}
} else {
	$comments_link = '';
}

$subtitle = $comments_link;

$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'blog',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

if ($full) {
	$body = elgg_view('output/longtext', [
		'value' => $blog->description,
		'class' => 'blog-post',
	]);

	$summary = '';
	if (elgg_extract('show_summary', $vars, true)) {
		$params = [
			'entity' => $blog,
			'title' => false,
			'metadata' => $metadata,
			'subtitle' => $subtitle,
		];
		$summary = elgg_view('object/elements/summary', $vars + $params);
	}

	$responses = '';
	if (elgg_extract('show_responses', $vars, false)) {
		// check to see if we should allow comments
		if ($blog->comments_on != 'Off' && $blog->status == 'published') {
			$responses = elgg_view_comments($blog);
		}
	}

	echo elgg_view('object/elements/full', [
		'entity' => $blog,
		'summary' => elgg_extract('show_summary', $vars, true) ? $summary : '',
		'icon' => $owner_icon,
		'body' => $body,
		'responses' => $responses,
	]);
} else {
	// brief view

	$params = [
		'entity' => $blog,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'content' => $excerpt,
		'icon' => $owner_icon,
	];
	$params = $params + $vars;
	echo elgg_view('object/elements/summary', $params);
}
