<?php
/**
 * Render a regular list item
 *
 * @uses $vars['item']      The item being rendered
 * @uses $vars['item_vars'] Vars received from the page/components/table view
 * @uses $vars['type']      The item type or ""
 */

$item = elgg_extract('item', $vars);
if (!$item instanceof ElggEntity) {
	return;
}

if (elgg_extract('type', $vars)) {
	// recognized type
	echo elgg_view_list_item($item, elgg_extract('item_vars', $vars));
}
