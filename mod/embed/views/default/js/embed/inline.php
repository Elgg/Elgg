<?php
/**
 * Inline embed JavaScript for attaching the insert data to list items
 *
 * @uses $vars['items']
 */

foreach ($vars['items'] as $item) {

	// different entity types have different title attribute names.
	$title = isset($item->name) ? $item->name : $item->title;
	// don't let it be too long
	$title = elgg_get_excerpt($title);

	$icon = "<img src=\"{$item->getIcon($icon_size)}\" />" . htmlspecialchars($title, ENT_QUOTES, 'UTF-8', false);

	$embed_code = elgg_view('output/url', array(
		'href' => $item->getURL(),
		'title' => $title,
		'text' => $icon,
		'encode_text' => false,
	));
	$embed_code = json_encode($embed_code);

	echo "$('#elgg-object-{$item->getGUID()}').data('embed_code', $embed_code);";
}