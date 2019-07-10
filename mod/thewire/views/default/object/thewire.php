<?php
/**
 * View a wire post
 *
 * @uses $vars['entity'] ElggWire to show
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggWire) {
	return;
}

elgg_require_js('elgg/thewire');

// make compatible with posts created with original Curverider plugin
$thread_id = $entity->wire_thread;
if (!$thread_id) {
	$entity->wire_thread = $entity->guid;
}

$params = [
	'title' => false,
	'tags' => false,
	'access' => false,
	'icon_entity' => $entity->getOwnerEntity(),
	'class' => 'thewire-post',
];

if (elgg_extract('full_view', $vars)) {
	$params['body'] = thewire_filter($entity->description);
	$params['show_summary'] = true;
	
	$params = $params + $vars;
	echo elgg_view('object/elements/full', $params);
} else {
	$params['content'] = thewire_filter($entity->description);
	
	$params = $params + $vars;
	echo elgg_view('object/elements/summary', $params);
}

if (!$entity->reply) {
	return;
}

echo elgg_format_element('div', [
	'class' => 'thewire-parent hidden',
	'id' => "thewire-previous-{$entity->guid}",
]);
