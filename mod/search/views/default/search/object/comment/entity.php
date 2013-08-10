<?php
/**
 * Default search view for a comment
 *
 * @uses $vars['entity'] Comment returned in a search
 */

$entity = $vars['entity'];

$owner = $entity->getOwnerEntity();
$icon = elgg_view_entity_icon($owner, 'tiny');

$container = $entity->getContainerEntity();

if ($container->getType() == 'object') {
	$title = $container->title;
} else {
	$title = $container->name;
}

if (!$title) {
	$title = elgg_echo('item:' . $container->getType() . ':' . $container->getSubtype());
}

if (!$title) {
	$title = elgg_echo('item:' . $container->getType());
}

$title = elgg_echo('search:comment_on', array($title));

$url = $entity->getURL();
$title = "<a href=\"$url\">$title</a>";

$description = $entity->getVolatileData('search_matched_description');

$title = "<a href=\"$url\">$title</a>";
$time = $entity->getVolatileData('search_time');

if (!$time) {
	$tc = $entity->time_created;
	$tu = $entity->time_updated;
	$time = elgg_view_friendly_time(($tu > $tc) ? $tu : $tc);
}

$body = "<p class=\"mbn\">$title</p>$description<p class=\"elgg-subtext\">$time</p>";

echo elgg_view_image_block($icon, $body);
