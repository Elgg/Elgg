<?php
/**
 * Render a regular list item
 *
 * @uses $vars['item']      The item being rendered
 * @uses $vars['item_vars'] Vars received from the page/components/table view
 * @uses $vars['type']      The item type or ""
 */

$item = $vars['item'];

if ($vars['type']) {
	// recognized type
	echo elgg_view_list_item($item, $vars['item_vars']);
}
