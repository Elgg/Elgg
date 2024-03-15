<?php
/**
 * Parent picker
 *
 * @uses $vars['value']          The current value, if any
 * @uses $vars['options_values']
 * @uses $vars['name']           The name of the input field
 * @uses $vars['entity']         Optional. The child entity (uses container_guid)
 */

$entity = elgg_extract('entity', $vars);
if ($entity instanceof ElggPage) {
	$container = $entity->getContainerEntity();
} else {
	$container = elgg_get_page_owner_entity();
}

if (!$container instanceof ElggEntity) {
	return;
}

$pages = pages_get_navigation_tree($container);

$options = [];
foreach ($pages as $page) {
	$spacing = str_repeat('--', $page['depth']);
	
	$options[] = [
		'text' => trim("{$spacing} {$page['title']}"),
		'value' => $page['guid'],
		'disabled' => $entity ? $entity->guid === $page['guid'] : null,
	];
}

$defaults = [
	'class' => 'elgg-pages-input-parent-picker',
	'options_values' => $options,
];

$vars = array_merge($defaults, $vars);

echo elgg_view('input/select', $vars);
