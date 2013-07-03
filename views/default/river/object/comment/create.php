<?php
/**
 * Post comment river view
 */

$comment = $vars['item']->getObjectEntity();

$summary = elgg_view('river/object/comment/summary', $vars);

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => elgg_get_excerpt($comment->description),
	'summary' => $summary,
));
