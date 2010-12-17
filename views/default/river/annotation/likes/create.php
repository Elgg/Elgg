<?php
/**
 * Likes river view
 */
$object = $vars['item']->getObjectEntity();
$comment = $vars['item']->getAnnotation();

$url = $object->getURL();
$title = $object->title;
if (!$title) {
	$title = elgg_echo('untitled');
}
$params = array(
	'href' => $object->getURL(),
	'text' => $title,
);
$object_link = elgg_view('output/url', $params);

$type = $object->getType();
$subtype = $object->getSubtype();

$type_string = elgg_echo("river:commented:$type:$subtype");
echo elgg_echo('river:likes', array($type_string, $object_link));
