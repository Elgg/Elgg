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
	'full_view' => false,
	'metadata' => false,
	'subtitle' => false,
]);

$body = '';
if (elgg_extract('show_owner_block_menu', $vars, true)) {
	$body .= elgg_view_menu('owner_block', ['entity' => $owner]);
}

$body .= elgg_view('page/elements/owner_block/extend', $vars);

echo elgg_view('page/components/module', [
	'header' => $header,
	'body' => $body,
	'class' => 'elgg-owner-block',
]);

elgg_pop_context();
