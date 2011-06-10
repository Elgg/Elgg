<?php
/**
 * Page river view.
 */

$object = $vars['item']->getObjectEntity();
$excerpt = strip_tags($object->description);
$excerpt = elgg_get_excerpt($excerpt);

echo elgg_view('river/item', array(
	'item' => $vars['item'],
	'message' => $excerpt,
));