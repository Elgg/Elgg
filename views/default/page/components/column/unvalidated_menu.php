<?php
/**
 * Render an unvalidated menu element
 *
 * @uses $vars['item']      The item being rendered
 * @uses $vars['item_vars'] Vars received from the page/components/table view
 * @uses $vars['type']      The item type or ""
 */

$item = elgg_extract('item', $vars);
if (!$item instanceof \ElggUser) {
	return;
}

echo elgg_view_menu('user:unvalidated', [
	'entity' => $item,
	'prepare_dropdown' => true,
	'class' => 'elgg-menu-entity', // for the dropdown elipsis styling
]);
