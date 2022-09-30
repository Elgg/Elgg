<?php
/**
 * File renderer
 *
 * @uses $vars['entity'] ElggFile to show
 */

$full = elgg_extract('full_view', $vars, false);
$entity = elgg_extract('entity', $vars, false);
if (!$entity instanceof \ElggFile) {
	return;
}

if ($full && !elgg_in_context('gallery')) {
	$mime = $entity->getMimeType() ?: '';
	$base_type = substr($mime, 0, strpos($mime, '/'));

	$extra = '';
	if (elgg_view_exists("file/specialcontent/{$mime}")) {
		$extra = elgg_view("file/specialcontent/{$mime}", $vars);
	} elseif (elgg_view_exists("file/specialcontent/{$base_type}/default")) {
		$extra = elgg_view("file/specialcontent/{$base_type}/default", $vars);
	}

	$body = elgg_view('output/longtext', ['value' => $entity->description]);

	$params = [
		'show_summary' => true,
		'icon_entity' => $entity->getOwnerEntity(),
		'body' => $body,
		'attachments' => $extra,
		'show_responses' => elgg_extract('show_responses', $vars, false),
		'show_navigation' => true,
	];
	$params = $params + $vars;
	
	echo elgg_view('object/elements/full', $params);
} elseif (elgg_in_context('gallery')) {
	$title = elgg_format_element('h3', [], $entity->getDisplayName());
	$icon = elgg_view_entity_icon($entity, 'large');

	echo elgg_format_element('div', ['class' => 'file-gallery-item'], $title . $icon);
} else {
	// brief view
	$params = [
		'content' => elgg_get_excerpt((string) $entity->description),
		'icon_entity' => $entity->getOwnerEntity(),
	];
	$params = $params + $vars;
	echo elgg_view('object/elements/summary', $params);
}
