<?php
/**
 * Layout of a river item
 *
 * @uses $vars['item'] ElggRiverItem
 */

$item = elgg_extract('item', $vars);
if (!$item instanceof \ElggRiverItem) {
	return;
}

$image = elgg_view('river/elements/image', $vars);
$body = elgg_view('river/elements/body', $vars);

$params = [
	'class' => 'elgg-river-item',
	'data-guid' => $item->object_guid,
];
echo elgg_view_image_block($image, $body, $params);
