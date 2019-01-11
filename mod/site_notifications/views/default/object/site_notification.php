<?php
/**
 * Site notification view
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof SiteNotification) {
	return;
}

$icon = '';
$text = $entity->description;
$actor = $entity->getActor();
if ($actor) {
	$icon = elgg_view_entity_icon($actor, 'small');
}
$url = $entity->getURL();
if ($url) {
	$text = elgg_view('output/url', [
		'text' => $text,
		'href' => $url,
		'is_trusted' => true,
		'class' => 'site-notifications-link',
		'data-guid' => $entity->guid,
	]);
}

$checkbox = elgg_view('input/checkbox', [
	'name' => 'notification_id[]',
	'value' => $entity->getGUID(),
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
