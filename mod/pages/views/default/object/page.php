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

if ($revision) {
	$annotation = $revision;
} else {
	$annotation = $entity->getAnnotations([
		'annotation_name' => 'page',
		'limit' => 1,
		'order_by' => [
			new \Elgg\Database\Clauses\OrderByClause('n_table.time_created', 'desc'),
			new \Elgg\Database\Clauses\OrderByClause('n_table.id', 'desc'),
		],
	]);
	if ($annotation) {
		$annotation = $annotation[0];
	} else {
		elgg_log("Failed to access annotation for page with GUID {$entity->guid}", 'WARNING');
		return;
	}
}

if (!$annotation instanceof ElggAnnotation) {
	return;
}

$icon_entity = null;
$owner = $annotation->getOwnerEntity();
if ($owner) {
	$icon_entity = $owner;
}

$metadata = null;
// If we're looking at a revision, display annotation menu
if ($revision) {
	$metadata = elgg_view_menu('annotation', [
		'annotation' => $annotation,
		'class' => 'elgg-menu-hz float-alt',
		'prepare_dropdown' => true,
	]);
}

if (elgg_extract('full_view', $vars)) {
	$body = elgg_view('output/longtext', ['value' => $annotation->value]);

	$params = [
		'metadata' => $metadata,
		'show_summary' => true,
		'icon_entity' => $icon_entity,
		'body' => $body,
		'show_responses' => elgg_extract('show_responses', $vars, false),
	];

	$params = $params + $vars;
	echo elgg_view('object/elements/full', $params);
} else {
	// brief view
	$params = [
		'metadata' => $metadata,
		'content' => elgg_get_excerpt($entity->description),
		'icon_entity' => $icon_entity,
	];
	$params = $params + $vars;
	echo elgg_view('object/elements/summary', $params);
}
