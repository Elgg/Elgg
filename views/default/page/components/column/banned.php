<?php
/**
 * Render "Yes" is a user is banned
 *
 * @uses $vars['item']      The item being rendered
 * @uses $vars['item_vars'] Vars received from the page/components/table view
 * @uses $vars['type']      The item type or ""
 */

$item = elgg_extract('item', $vars);
if (!$item instanceof ElggUser) {
	return;
}

if ($item->banned === 'yes') {
	echo elgg_echo('option:yes');
}
