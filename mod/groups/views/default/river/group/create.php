<?php
/**
 * Group creation river view.
 */

$item = elgg_extract('item', $vars);
if (!$item instanceof ElggRiverItem) {
	return;
}

$vars['message'] = elgg_get_excerpt($item->getObjectEntity()->description);

echo elgg_view('river/elements/layout', $vars);
