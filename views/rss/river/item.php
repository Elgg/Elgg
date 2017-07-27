<?php
/**
 * RSS river view
 *
 * @uses $vars['item']
 */

$item = $vars['item'];
/** @var ElggRiverItem $item */

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
