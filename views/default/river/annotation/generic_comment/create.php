<?php
/**
 * Post comment river view
 */
$object = $vars['item']->getObjectEntity();
$comment = $vars['item']->getAnnotation();

echo elgg_view('river/item', array(
	'item' => $vars['item'],
	'message' => elgg_get_excerpt($comment->value),
));
