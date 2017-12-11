<?php
/**
 * View a wire post
 *
 * @uses $vars['entity']
 */

$post = elgg_extract('entity', $vars);
if (!$post instanceof \ElggWire) {
	return;
}

elgg_require_js('elgg/thewire');

// make compatible with posts created with original Curverider plugin
$thread_id = $post->wire_thread;
if (!$thread_id) {
	$post->wire_thread = $post->guid;
}

$params = [
	'entity' => $post,
	'title' => false,
	'handler' => 'thewire',
	'content' => thewire_filter($post->description),
	'tags' => false,
	'icon' => elgg_view_entity_icon($post->getOwnerEntity(), 'small'),
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
