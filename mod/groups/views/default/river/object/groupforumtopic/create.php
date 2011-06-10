<?php
/**
 * Group forum topic create river view.
 */

$object = $vars['item']->getObjectEntity();
$excerpt = strip_tags($object->description);
$excerpt = elgg_get_excerpt($excerpt);

echo elgg_view('river/item', array(
	'item' => $vars['item'],
	'message' => $excerpt,
));

if (elgg_is_logged_in() && $object->canAnnotate(0, 'group_topic_post')) {
	// inline comment form
	$form_vars = array('id' => "groups-reply-{$object->getGUID()}", 'class' => 'hidden');
	$body_vars = array('entity' => $object, 'inline' => true);
	echo elgg_view_form('discussion/reply/save', $form_vars, $body_vars);
}
