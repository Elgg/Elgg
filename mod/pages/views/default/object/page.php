<?php
/**
 * View for page object
 *
 * @uses $vars['entity']   The page object
 * @uses $vars['revision'] This parameter not supported by elgg_view_entity()
 */

$entity = elgg_extract('entity', $vars);
$revision = elgg_extract('revision', $vars, false);
if (!$entity instanceof ElggPage) {
	return;
}

// pages used to use Public for write access
if ($entity->write_access_id == ACCESS_PUBLIC) {
	// this works because this metadata is public
	$entity->write_access_id = ACCESS_LOGGED_IN;
}

$description = (string) $entity->description;

$byline_owner_entity = null;
$icon_entity = null;
$metadata = null;

// If we're looking at a revision, display annotation menu
if ($revision instanceof \ElggAnnotation) {
	$metadata = elgg_view_menu('annotation', [
		'annotation' => $revision,
		'class' => 'elgg-menu-hz',
		'prepare_dropdown' => true,
	]);
	
	$description = (string) $revision->value;
	
	$owner = $revision->getOwnerEntity();
	if ($owner instanceof \ElggEntity) {
		$icon_entity = $owner;
		$byline_owner_entity = $owner;
	}
}

if (elgg_extract('full_view', $vars)) {
	$params = [
		'metadata' => $metadata,
		'show_summary' => true,
		'icon' => true,
		'icon_entity' => $icon_entity,
		'byline_owner_entity' => $byline_owner_entity,
		'body' => elgg_view('output/longtext', [
			'value' => $description,
		]),
		'show_responses' => elgg_extract('show_responses', $vars, false),
	];
	
	$params = $params + $vars;
	echo elgg_view('object/elements/full', $params);
} else {
	// brief view
	$params = [
		'metadata' => $metadata,
		'content' => elgg_get_excerpt($description),
		'icon' => true,
	];
	$params = $params + $vars;
	echo elgg_view('object/elements/summary', $params);
}
