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

if ($full && !elgg_in_context('gallery')) {
	$mime = $file->getMimeType();
	$base_type = substr($mime, 0, strpos($mime, '/'));

	$extra = '';
	if (elgg_view_exists("file/specialcontent/$mime")) {
		$extra = elgg_view("file/specialcontent/$mime", $vars);
	} elseif (elgg_view_exists("file/specialcontent/$base_type/default")) {
		$extra = elgg_view("file/specialcontent/$base_type/default", $vars);
	}

	$params = [
		'title' => false,
	];
	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

	$body = elgg_view('output/longtext', ['value' => $file->description]);

	echo elgg_view('object/elements/full', [
		'entity' => $file,
		'icon' => elgg_view_entity_icon($owner, 'small'),
		'summary' => $summary,
		'body' => $body,
		'attachments' => $extra,
		'show_responses' => elgg_extract('show_responses', $vars, false),
		'show_navigation' => true,
	]);
} elseif (elgg_in_context('gallery')) {
	echo '<div class="file-gallery-item">';
	echo "<h3>" . $file->getDisplayName() . "</h3>";
	echo elgg_view_entity_icon($file, 'medium');
	echo '</div>';
} else {
	// brief view
	$params = [
		'entity' => $file,
		'content' => elgg_get_excerpt($file->description),
		'icon' => elgg_view_entity_icon($file, 'small'),
	];
	$params = $params + $vars;
	echo elgg_view('object/elements/summary', $params);
}
