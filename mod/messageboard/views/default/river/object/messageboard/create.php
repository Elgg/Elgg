<?php
/**
 * Messageboard river view
 */

$item = $vars['item'];
/* @var ElggRiverItem $item */

$messageboard = $item->getAnnotation();
$excerpt = elgg_get_excerpt($messageboard->value);

echo elgg_view('river/elements/layout', array(
	'item' => $item,
	'message' => $excerpt,
));
