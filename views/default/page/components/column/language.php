<?php
/**
 * Render a user's language
 *
 * @uses $vars['item']      The item being rendered
 * @uses $vars['item_vars'] Vars received from the page/components/table view
 * @uses $vars['type']      The item type or ""
 */

$item = elgg_extract('item', $vars);
if (!$item instanceof ElggUser) {
	return;
}

if (!$item->language) {
	return;
}

echo elgg_echo($item->language);
