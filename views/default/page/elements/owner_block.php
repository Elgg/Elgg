<?php
/**
 * Elgg owner block
 * Displays page ownership information
 *
 * @uses $vars['show_owner_block'] (bool) Display owner block (default: true)
 * @uses $vars['show_owner_block_menu'] (bool) Show the owner_block menu for the current page owner (default: true)
 */

if (!elgg_extract('show_owner_block', $vars, true)) {
	return;
}

$owner = elgg_get_page_owner_entity();
if (!$owner instanceof \ElggGroup && !$owner instanceof \ElggUser) {
	return;
}

$body = '';
if (elgg_extract('show_owner_block_menu', $vars, true)) {
	$menu_params = elgg_extract('owner_block_menu_params', $vars, []);
	$menu_params['entity'] = $owner;
	$menu_params['prepare_vertical'] = true;
	
	$body .= elgg_view_menu('owner_block', $menu_params);
}

if (elgg_view_exists('page/elements/owner_block/extend')) {
	$body .= elgg_view('page/elements/owner_block/extend', $vars);
}

if (empty($body)) {
	return;
}

echo elgg_view_module('aside', '', $body, [
	'header' => elgg_view_entity($owner, ['item_view' => 'object/elements/chip']),
]);
