<?php
/**
 * Site notification view
 */

$entity = $vars['entity'];

$text = $entity->description;
$actor = $entity->getActor();
if ($actor) {
	$icon = elgg_view_entity_icon($actor, 'tiny');
}
$url = $entity->getURL();
if ($url) {
	$text = elgg_view('output/url', array(
		'text' => $text,
		'href' => $url,
		'is_trusted' => true,
	));
}

echo elgg_view_image_block($icon, $text, array('class' => $class));
