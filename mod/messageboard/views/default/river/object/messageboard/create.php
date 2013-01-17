<?php
/**
 * Messageboard river view
 */

$messageboard = $vars['item']->getAnnotation();
$excerpt = elgg_get_excerpt($messageboard->value);

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => $excerpt,
));
