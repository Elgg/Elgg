<?php
/**
 * Render "Yes" where a user is an admin
 *
 * @uses $vars['item']      The item being rendered
 * @uses $vars['item_vars'] Vars received from the page/components/table view
 * @uses $vars['type']      The item type or ""
 */

$item = $vars['item'];
/* @var ElggUser $item */

if ($item->admin === 'yes') {
	echo elgg_echo('option:yes');
}
