<?php
/**
 * New bookmarks river entry
 *
 * @package Bookmarks
 */

$item = $vars['item'];
/* @var ElggRiverItem $item */

$object = $item->getObjectEntity();
$excerpt = elgg_get_excerpt($object->description);

echo elgg_view('river/elements/layout', [
	'item' => $item,
	'message' => $excerpt,
	'attachments' => elgg_view('output/url', ['href' => $object->address]),
]);
