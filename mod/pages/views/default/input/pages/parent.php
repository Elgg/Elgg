<?php
/**
 * Parent picker
 *
 * @uses $vars['value']          The current value, if any
 * @uses $vars['options_values']
 * @uses $vars['name']           The name of the input field
 * @uses $vars['entity']         Optional. The child entity (uses container_guid)
 */

elgg_load_library('elgg:pages');

$entity = elgg_extract('entity', $vars);
if ($entity instanceof ElggEntity) {
	$container = $entity->getContainerEntity();
} else {
	$container = elgg_get_page_owner_entity();
}

$pages = pages_get_navigation_tree($container);

$options = [];

foreach ($pages as $page) {
	$spacing = "";
	for ($i = 0; $i < $page['depth']; $i++) {
		$spacing .= "--";
	}
	$options[$page['guid']] = "$spacing " . $page['title'];
}

$defaults = array(
	'class' => 'elgg-pages-input-parent-picker',
	'options_values' => $options,
);

$vars = array_merge($defaults, $vars);

echo elgg_view('input/select', $vars);
