<?php
/**
 * River view for new discussion topics
 */

$item = elgg_extract('item', $vars);
if (!$item instanceof ElggRiverItem) {
	return;
}

$object = $item->getObjectEntity();
$vars['message'] = elgg_get_excerpt($object->description);
$vars['responses'] = elgg_view('river/elements/discussion_replies', [
	'topic' => $object,
]);

echo elgg_view('river/elements/layout', $vars);
