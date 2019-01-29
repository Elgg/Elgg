<?php
/**
 * Embeddable content list item view
 *
 * @uses $vars['entity'] ElggEntity object
 */

$entity = elgg_extract('entity', $vars);

$title = elgg_get_excerpt($entity->getDisplayName());

$icon = elgg_view_entity_icon($entity, 'small', ['link_class' => 'embed-insert']);

echo elgg_view('object/elements/summary', [
	'title' => $title,
	'entity' => $entity,
	'show_links' => false,
	'metadata' => false,
	'tags' => false,
	'icon' => $icon,
]);
