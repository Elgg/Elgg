<?php
/**
 * RSS river view
 *
 * @uses $vars['item']
 */

$item = $vars['item'];

$output = elgg_view($item->getView(), $vars);

if (empty($output)) {
	$output = elgg_view($item->getView(), $vars, false, false, 'default');
}

$rss_item = <<<__ITEM
<item>
	$output
</item>
__ITEM;

echo $rss_item;
