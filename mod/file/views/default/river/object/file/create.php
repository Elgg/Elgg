<?php
/**
 * File river view.
 */

$item = elgg_extract('item', $vars);
/* @var ElggRiverItem $item */

echo elgg_view('river/elements/layout', [
	'item' => $item,
	'message' => elgg_get_excerpt($item->getObjectEntity()->description),
]);
