<?php
/**
 * Layout of a river item
 *
 * @uses $vars['item'] ElggRiverItem
 */

$item = elgg_extract('item', $vars);
if (!$item instanceof ElggRiverItem) {
	return;
}

$action = $item->action;

$classes = [
	'elgg-river-item',
	"elgg-river-action-$action",
];

$object = $item->getObjectEntity();
if ($object) {
	$classes[] = "elgg-river-object-{$object->type}-{$object->subtype}";
}

$result = $item->getResult();
if ($result) {
	$classes[] = "elgg-river-result-{$result->getType()}-{$result->getSubtype()}";
}

$vars['image'] = elgg_view('river/elements/image', $vars);
$vars['body'] = elgg_view('river/elements/body', $vars);
$vars['class'] = elgg_extract_class($vars, $classes);

echo elgg_view('page/components/image_block', $vars);
