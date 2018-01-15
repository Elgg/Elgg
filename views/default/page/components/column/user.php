<?php
/**
 * Render a user icon and name
 *
 * @uses $vars['item']      The item being rendered
 * @uses $vars['item_vars'] Vars received from the page/components/table view
 * @uses $vars['type']      The item type or ""
 */

$item = elgg_extract('item', $vars);
if (!$item instanceof ElggUser) {
	return;
}

$vars['item_view'] = 'user/default/column';
$vars['full_view'] = false;

echo elgg_view_entity($item, $vars);
