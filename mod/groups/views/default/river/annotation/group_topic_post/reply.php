<?php
/**
 * Reply river view
 */
$object = $vars['item']->getObjectEntity();
$reply = $vars['item']->getAnnotation();
$excerpt = elgg_get_excerpt($reply->value);

echo elgg_view('river/item', array(
	'item' => $vars['item'],
	'message' => $excerpt,
));