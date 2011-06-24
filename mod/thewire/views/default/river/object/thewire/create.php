<?php
/**
 * File river view.
 */

$object = $vars['item']->getObjectEntity();
$excerpt = strip_tags($object->description);
$excerpt = thewire_filter($excerpt);

echo elgg_view('river/item', array(
	'item' => $vars['item'],
	'message' => $excerpt,
	'summary' => false,
));