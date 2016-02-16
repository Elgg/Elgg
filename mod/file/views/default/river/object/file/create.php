<?php
/**
 * File river view.
 */

$item = $vars['item'];
/* @var ElggRiverItem $item */

$object = $item->getObjectEntity();
$excerpt = strip_tags($object->description);
$excerpt = elgg_get_excerpt($excerpt);

$mime = $object->mimetype;
$base_type = substr($mime, 0, strpos($mime,'/'));

$params = array(
	'entity' => $object,
	'full_view' => true,
);

$attachment = '';
if (elgg_view_exists("file/specialcontent/$mime")) {
	$attachment = elgg_view("file/specialcontent/$mime", $params );
} else if (elgg_view_exists("file/specialcontent/$base_type/default")) {
	$attachment = elgg_view("file/specialcontent/$base_type/default", $params);
}

echo elgg_view('river/elements/layout', array(
	'item' => $item,
	'message' => $excerpt,
	'attachments' => $attachment,
));