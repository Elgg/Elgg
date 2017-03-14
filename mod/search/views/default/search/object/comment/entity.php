<?php
/**
 * Default search view for a comment
 *
 * @uses $vars['entity'] Comment returned in a search
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggEntity) {
	return;
}

$owner = $entity->getOwnerEntity();
$icon = elgg_view_entity_icon($owner, 'tiny');

$container = $entity->getContainerEntity();

$title_text = $container->getDisplayName();

if (!$title_text) {
	$title_text = elgg_echo("item:{$container->getType()}:{$container->getSubtype()}");
}

if (!$title_text) {
	$title_text = elgg_echo("item:{$container->getType()}");
}

$title_text = elgg_echo('search:comment_on', [$title_text]);

$title = elgg_view('output/url', [
	'href' => $entity->getURL(),
	'text' => $title_text,
]);

$description = $entity->getVolatileData('search_matched_description');

$time = $entity->getVolatileData('search_time');

if (!$time) {
	$tc = $entity->time_created;
	$tu = $entity->time_updated;
	$time = elgg_view_friendly_time(($tu > $tc) ? $tu : $tc);
}

$body = "<p class=\"mbn\">$title</p>$description<p class=\"elgg-subtext\">$time</p>";

echo elgg_view_image_block($icon, $body);
