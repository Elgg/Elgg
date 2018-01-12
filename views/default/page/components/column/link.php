<?php
/**
 * Render a linked title
 *
 * @uses $vars['item']      The item being rendered
 * @uses $vars['item_vars'] Vars received from the page/components/table view
 * @uses $vars['type']      The item type or ""
 */

$item = elgg_extract('item', $vars);
if (!$item instanceof ElggEntity) {
	return;
}

echo elgg_view('output/url', [
	'href' => $item->getURL(),
	'text' => $item->getDisplayName(),
]);
