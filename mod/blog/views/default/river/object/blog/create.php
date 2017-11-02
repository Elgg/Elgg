<?php
/**
 * Blog river view.
 */

$item = elgg_extract('item', $vars);
if (!$item instanceof ElggRiverItem) {
	return;
}

$blog = $item->getObjectEntity();
if (!$blog instanceof ElggBlog) {
	return;
}

$vars['message'] = $blog->getExcerpt();

echo elgg_view('river/elements/layout', $vars);
