<?php
/**
 * Blog river view.
 */

$item = elgg_extract('item', $vars);
/* @var ElggRiverItem $item */

$blog = $item->getObjectEntity();
if (!$blog instanceof ElggBlog) {
	return;
}

echo elgg_view('river/elements/layout', [
	'item' => $item,
	'message' => $blog->getExcerpt(),
]);
