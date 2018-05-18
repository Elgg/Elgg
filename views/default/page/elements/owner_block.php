<?php
/**
 * Elgg owner block
 * Displays page ownership information
 *
 * @uses $vars['show_owner_block_menu'] (bool) Show the owner_block menu for the current page owner (default: true)
 *
 * @package Elgg
 * @subpackage Core
 */

// groups and other users get owner block
$owner = elgg_get_page_owner_entity();
if (!($owner instanceof ElggGroup || $owner instanceof ElggUser)) {
	return;
}

elgg_push_context('owner_block');

$header = elgg_view_entity($owner, [
	'item_view' => 'object/elements/chip',
]);

$extra_class = '';
$body = '';
if (elgg_extract('show_owner_block_menu', $vars, true)) {
	$body .= elgg_view_menu('owner_block', ['entity' => $owner]);
} else {
	$extra_class = 'elgg-owner-block-empty';
}

if (elgg_view_exists('page/elements/owner_block/extend')) {
	$body .= elgg_view('page/elements/owner_block/extend', $vars);
}

echo elgg_view_module('info', '', $body, [
	'header' => $header,
	'class' => ['elgg-owner-block', $extra_class],
]);

elgg_pop_context();
