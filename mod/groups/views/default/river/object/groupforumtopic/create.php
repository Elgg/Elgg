<?php
/**
 * Group forum topic create river view.
 */

$object = $vars['item']->getObjectEntity();
$excerpt = strip_tags($object->description);
$excerpt = elgg_get_excerpt($excerpt);

$responses = '';
if (elgg_is_logged_in() && $object->canWriteToContainer()) {
	// inline comment form
	$form_vars = array('id' => "groups-reply-{$object->getGUID()}", 'class' => 'hidden');
	$body_vars = array('topic' => $object, 'inline' => true);
	$responses = elgg_view_form('discussion/reply/save', $form_vars, $body_vars);
}

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => $excerpt,
	'responses' => $responses,
));
