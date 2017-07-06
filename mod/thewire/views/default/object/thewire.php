<?php
/**
 * View a wire post
 *
 * @uses $vars['entity']
 */

elgg_require_js('elgg/thewire');

$full = elgg_extract('full_view', $vars, false);
$post = elgg_extract('entity', $vars, false);

if (!$post) {
	return true;
}

// make compatible with posts created with original Curverider plugin
$thread_id = $post->wire_thread;
if (!$thread_id) {
	$post->wire_thread = $post->guid;
}

$subtitle = elgg_view('object/elements/imprint', $vars);

$metadata = '';
if (!elgg_in_context('widgets')) {
	// only show entity menu outside of widgets
	$metadata = elgg_view_menu('entity', [
		'entity' => $post,
		'handler' => 'thewire',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	]);
}

$params = [
	'entity' => $post,
	'title' => false,
	'metadata' => $metadata,
	'subtitle' => $subtitle,
	'content' => thewire_filter($post->description),
	'tags' => false,
	'icon' => elgg_view_entity_icon($post->getOwnerEntity(), 'tiny'),
	'class' => 'thewire-post',
];
$params = $params + $vars;
echo elgg_view('object/elements/summary', $params);

if (!$post->reply) {
	return;
}

echo elgg_format_element('div', [
	'class' => 'thewire-parent hidden',
	'id' => "thewire-previous-{$post->guid}",
]);
