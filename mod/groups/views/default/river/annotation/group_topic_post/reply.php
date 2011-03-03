<?php
/**
 * Reply river view
 */
$object = $vars['item']->getObjectEntity();
$reply = $vars['item']->getAnnotation();

$url = $object->getURL();
$title = $object->title;
$params = array(
	'href' => $object->getURL(),
	'text' => $title,
);
$object_link = elgg_view('output/url', $params);

$type = $object->getType();
$subtype = $object->getSubtype();

echo elgg_echo('groups:river:reply') . ' ';
echo $object_link;

if ($reply) {
	$excerpt = elgg_get_excerpt($reply->value);
	echo '<div class="elgg-river-content">';
	echo $excerpt;
	echo '</div>';
}

