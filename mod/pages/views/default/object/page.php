<?php
/**
 * View for page object
 *
 * @uses $vars['entity']    The page object
 * @uses $vars['full_view'] Whether to display the full view
 * @uses $vars['revision']  This parameter not supported by elgg_view_entity()
 */

$full = elgg_extract('full_view', $vars, false);
$page = elgg_extract('entity', $vars, false);
$revision = elgg_extract('revision', $vars, false);

if (!$page instanceof ElggPage) {
	return;
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
		'order_by' => 'n_table.time_created desc, n_table.id desc',
	]);
	if ($annotation) {
		$annotation = $annotation[0];
	} else {
		elgg_log("Failed to access annotation for page with GUID {$page->guid}", 'WARNING');
		return;
	}
}

$owner = $annotation->getOwnerEntity();
$owner_icon = elgg_view_entity_icon($owner, 'tiny');

$metadata = null;
// If we're looking at a revision, display annotation menu
if ($revision) {
	$metadata = elgg_view_menu('annotation', [
		'annotation' => $annotation,
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz float-alt',
	]);
}

if ($full) {
	$body = elgg_view('output/longtext', ['value' => $annotation->value]);

	$params = [
		'entity' => $page,
		'metadata' => $metadata,
		'handler' => 'pages',
		'title' => false,
	];

	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

	echo elgg_view('object/elements/full', [
		'entity' => $page,
		'icon' => $owner_icon,
		'summary' => $summary,
		'body' => $body,
		'show_responses' => elgg_extract('show_responses', $vars, false),
	]);
} else {
	// brief view
	$params = [
		'entity' => $page,
		'metadata' => $metadata,
		'handler' => 'pages',
		'content' => elgg_get_excerpt($page->description),
		'icon' => $owner_icon,
	];
	$params = $params + $vars;
	echo elgg_view('object/elements/summary', $params);
}
