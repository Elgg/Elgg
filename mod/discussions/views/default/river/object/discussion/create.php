<?php
/**
 * River view for new discussion topics
 */

$item = $vars['item'];
/* @var ElggRiverItem $item */

$object = $item->getObjectEntity();
$excerpt = strip_tags($object->description);
$excerpt = elgg_get_excerpt($excerpt);

$responses = elgg_view('river/elements/discussion_replies', array('topic' => $object));

echo elgg_view('river/elements/layout', array(
	'item' => $item,
	'message' => $excerpt,
	'responses' => $responses,
));
