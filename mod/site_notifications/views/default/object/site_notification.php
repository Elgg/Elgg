<?php
/**
 * Site notification view
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof SiteNotification) {
	return;
}

$text = $entity->description;
$actor = $entity->getActor();

$icon = $actor ? elgg_view_entity_icon($actor, 'small') : '';

$url = $entity->getURL();
if ($url) {
	$text = elgg_view('output/url', [
		'text' => $text,
		'href' => elgg_generate_action_url('entity/delete', [
			'guid' => $entity->guid,
			'forward_url' => $url,
			'show_success' => false,
		]),
	]);
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
	'byline' => false,
	'access' => false,
	'show_social_menu' => false,
];
$params = $params + $vars;
echo elgg_view('object/elements/summary', $params);
