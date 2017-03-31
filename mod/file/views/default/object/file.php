<?php

/**
 * File renderer.
 *
 * @package ElggFile
 */
$full = elgg_extract('full_view', $vars, false);
$file = elgg_extract('entity', $vars, false);

if (!$file) {
	return true;
}

$owner = $file->getOwnerEntity();

$metadata = '';
if (!elgg_in_context('gallery')) {
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

	$summary = '';
	if (elgg_extract('show_summary', $vars, true)) {
		$params = [
			'entity' => $file,
			'title' => false,
			'metadata' => $metadata,
		];
		$params = $params + $vars;
		$summary = elgg_view('object/elements/summary', $params);
	}

	$body = elgg_view('output/longtext', ['value' => $file->description]);

	$owner_icon = elgg_view_entity_icon($owner, 'small');

	$responses = '';
	if (elgg_extract('show_responses', $vars, false)) {
		$responses = elgg_view_comments($file);
	}

	echo elgg_view('object/elements/full', [
		'entity' => $file,
		'icon' => $owner_icon,
		'summary' => $summary,
		'body' => $body,
		'attachments' => $extra,
		'responses' => $responses,
	]);
} elseif (elgg_in_context('gallery')) {
	echo elgg_view('object/file/card', $vars);
} else {
	// brief view
	$excerpt = elgg_get_excerpt($file->description);

	$file_icon = elgg_view_entity_icon($file, 'small');

	$params = [
		'entity' => $file,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'content' => $excerpt,
		'icon' => $file_icon,
	];
	$params = $params + $vars;
	echo elgg_view('object/elements/summary', $params);
}
