<?php
/**
 * Group forum topic create river view.
 */

$object = $vars['item']->getObjectEntity();
$excerpt = strip_tags($object->description);
$excerpt = elgg_get_excerpt($excerpt);

$responses = '';
if (elgg_is_logged_in() && $object->canWriteToContainer()) {
	$responses = elgg_view('river/elements/discussion_replies', array('topic' => $object));
}

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => $excerpt,
	'responses' => $responses,
));
