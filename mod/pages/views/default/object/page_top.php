<?php

/**
 * View for page object
 *
 * @package ElggPages
 *
 * @uses $vars['entity']    The page object
 * @uses $vars['full_view'] Whether to display the full view
 * @uses $vars['revision']  This parameter not supported by elgg_view_entity()
 */
$full = elgg_extract('full_view', $vars, false);
$page = elgg_extract('entity', $vars, false);
$revision = elgg_extract('revision', $vars, false);

if (!$page) {
	return true;
}

// pages used to use Public for write access
if ($page->write_access_id == ACCESS_PUBLIC) {
	// this works because this metadata is public
	$page->write_access_id = ACCESS_LOGGED_IN;
}


if ($revision) {
	$annotation = $revision;
} else {
	$annotation = $page->getAnnotations([
		'annotation_name' => 'page',
		'limit' => 1,
		'reverse_order_by' => true,
	]);
	if ($annotation) {
		$annotation = $annotation[0];
	} else {
		elgg_log("Failed to access annotation for page with GUID {$page->guid}", 'WARNING');
		return;
	}
}

$page_icon = elgg_view('pages/icon', ['annotation' => $annotation, 'size' => 'small']);

$comments_count = $page->countComments();
//only display if there are commments
if ($comments_count != 0 && !$revision) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', [
		'href' => $page->getURL() . '#comments',
		'text' => $text,
		'is_trusted' => true,
	]);
} else {
	$comments_link = '';
}

$subtitle = "$comments_link";

if ($revision) {
	$metadata = elgg_view_menu('annotation', array(
		'annotation' => $annotation,
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz float-alt',
	));
} else {
	// Regular entity menu
	$metadata = elgg_view_menu('entity', array(
		'entity' => $vars['entity'],
		'handler' => 'pages',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	));
}

if ($full) {
	$body = elgg_view('output/longtext', ['value' => $annotation->value]);

	$summary = '';
	if (elgg_extract('show_summary', $vars, true)) {
		$params = [
			'entity' => $page,
			'metadata' => $metadata,
			'title' => false,
			'subtitle' => $subtitle,
		];
		$summary = elgg_view('object/elements/summary', $vars + $params);
	}

	$responses = '';
	if (elgg_extract('show_responses', $vars, false)) {
		$responses = elgg_view_comments($page);
	}

	echo elgg_view('object/elements/full', [
		'entity' => $page,
		'icon' => $page_icon,
		'summary' => $summary,
		'body' => $body,
		'responses' => $responses,
	]);
} else {
	// brief view

	$excerpt = elgg_get_excerpt($page->description);

	$params = [
		'entity' => $page,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'content' => $excerpt,
		'icon' => $page_icon,
	];
	$params = $params + $vars;
	echo elgg_view('object/elements/summary', $params);
}
