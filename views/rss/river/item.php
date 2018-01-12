<?php
/**
 * RSS river view
 *
 * @uses $vars['item']
 */

$item = elgg_extract('item', $vars);
if (!$item instanceof ElggRiverItem) {
	return;
}

$output = elgg_view($item->getView(), $vars);

if (empty($output)) {
	$output = elgg_view($item->getView(), $vars, 'default');
}

$rss_item = <<<__ITEM
<item>
	$output
</item>
__ITEM;

echo $rss_item;
