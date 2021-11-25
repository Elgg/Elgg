<?php
/**
 * View for blog objects
 *
 * @uses $vars['entity'] ElggBlog entity to show
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggBlog) {
	return;
}

if (!isset($vars['imprint'])) {
	$vars['imprint'] = [];
}

if ($entity->status && $entity->status !== 'published') {
	$vars['imprint'][] = [
		'icon_name' => 'warning',
		'content' => elgg_echo("status:{$entity->status}"),
		'class' => 'elgg-listing-blog-status',
	];
	
	// Show the access the blog will have when published
	$vars['access'] = $entity->future_access;
}

if (elgg_extract('full_view', $vars)) {
	$body = elgg_view('output/longtext', [
		'value' => $entity->description,
		'class' => 'blog-post',
	]);

	$params = [
		'icon' => true,
		'body' => $body,
		'show_summary' => true,
		'show_navigation' => true,
	];
	$params = $params + $vars;
	
	echo elgg_view('object/elements/full', $params);
} else {
	// brief view
	$params = [
		'content' => $entity->getExcerpt(),
		'icon' => true,
	];
	$params = $params + $vars;
	echo elgg_view('object/elements/summary', $params);
}
