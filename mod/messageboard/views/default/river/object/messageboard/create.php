<?php
/**
 * Messageboard river view
 */

$item = elgg_extract('item', $vars);
if (!$item instanceof ElggRiverItem) {
	return;
}

$messageboard = $item->getAnnotation();
$vars['message'] = elgg_get_excerpt($messageboard->value);

echo elgg_view('river/elements/layout', $vars);
