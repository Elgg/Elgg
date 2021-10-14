<?php
/**
 * Site notification view
 *
 * @uses $vars['entity'] the site notification
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof SiteNotification) {
	return;
}

$text = $entity->getDisplayName();
$actor = $entity->getActor();
$linked_entity = $entity->getLinkedEntity();

$icon = $actor ? elgg_view_entity_icon($actor, 'small') : '';

if ($entity->getURL()) {
	$text = elgg_view_url(elgg_generate_entity_url($entity, 'redirect'), $text);
}

$checkbox = elgg_view('input/checkbox', [
	'name' => 'notification_id[]',
	'value' => $entity->guid,
	'default' => false,
]);

$params = [
	'entity' => $entity,
	'icon' => $checkbox . $icon,
	'title' => $text,
	'byline_owner_entity' => false,
	'byline_container_entity' => false,
	'access' => false,
	'show_social_menu' => false,
];

if ($linked_entity instanceof \ElggEntity) {
	$container = $linked_entity->getContainerEntity();
	if ($container instanceof \ElggGroup) {
		$params['byline_container_entity'] = $container;
	}
}

$params = $params + $vars;

echo elgg_view('object/elements/summary', $params);
