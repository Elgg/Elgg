<?php
/**
 * File renderer.
 *
 * @package ElggFile
 */

$full = elgg_extract('full_view', $vars, false);
$file = elgg_extract('entity', $vars, false);

if (!($file instanceof \ElggFile)) {
	return;
}

$owner = $file->getOwnerEntity();
$categories = elgg_view('output/categories', $vars);

$by_line = elgg_view('object/elements/imprint', $vars);

$comments_link = '';
$comments_count = $file->countComments();
//only display if there are commments
if ($comments_count != 0) {
	$comments_link = elgg_view('output/url', [
		'href' => $file->getURL() . '#comments',
		'text' => elgg_echo('comments') . " ($comments_count)",
		'is_trusted' => true,
	]);
}

$subtitle = "$by_line $comments_link $categories";

$metadata = '';
if (!elgg_in_context('widgets') && !elgg_in_context('gallery')) {
	// only show entity menu outside of widgets and gallery view
	$metadata = elgg_view_menu('entity', [
		'entity' => $vars['entity'],
		'handler' => 'file',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	]);
}

if ($full && !elgg_in_context('gallery')) {
	$mime = $file->getMimeType();
	$base_type = substr($mime, 0, strpos($mime, '/'));

	$extra = '';
	if (elgg_view_exists("file/specialcontent/$mime")) {
		$extra = elgg_view("file/specialcontent/$mime", $vars);
	} else if (elgg_view_exists("file/specialcontent/$base_type/default")) {
		$extra = elgg_view("file/specialcontent/$base_type/default", $vars);
	}

	$params = [
		'entity' => $file,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
	];
	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

	$body = elgg_view('output/longtext', ['value' => $file->description]);

	$responses = '';
	if (elgg_extract('show_responses', $vars, false)) {
		$responses = elgg_view_comments($file);
	}

	echo elgg_view('object/elements/full', [
		'entity' => $file,
		'icon' => elgg_view_entity_icon($owner, 'small'),
		'summary' => $summary,
		'body' => $body,
		'attachments' => $extra,
		'responses' => $responses,
		'show_navigation' => true,
	]);
} elseif (elgg_in_context('gallery')) {
	echo '<div class="file-gallery-item">';
	echo "<h3>" . $file->getDisplayName() . "</h3>";
	echo elgg_view_entity_icon($file, 'medium');
	echo "<p class='subtitle'>$owner_link $date</p>";
	echo '</div>';
} else {
	// brief view
	$params = [
		'entity' => $file,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'content' => elgg_get_excerpt($file->description),
		'icon' => elgg_view_entity_icon($file, 'small'),
	];
	$params = $params + $vars;
	echo elgg_view('object/elements/summary', $params);
}
