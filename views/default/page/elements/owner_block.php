<?php

/**
 * Elgg owner block
 * Displays page ownership information
 *
 * @uses $vars['owner_block_class']  Class
 * @uses $vars['owner_block']        Override owner block
 * @uses $vars['owner_block_header'] Override owner block header
 */

$owner = elgg_extract('owner_block_owner', $vars);
if (!isset($owner)) {
	$owner = elgg_get_page_owner_entity();
}
if (!$owner instanceof ElggGroup && !$owner instanceof ElggUser) {
	return;
}

elgg_push_context('owner_block');

$owner_block = elgg_extract('owner_block', $vars);
if (!isset($owner_block)) {
	$owner_block = elgg_view('page/components/module', [
		'header' => elgg_view('page/elements/owner_block/header', $vars),
		'body' => elgg_view('page/elements/owner_block/body', $vars),
		'footer' => elgg_view('page/elements/owner_block/footer', $vars),
		'class' => 'elgg-owner-block',
	]);
}

if ($owner_block) {
	echo $owner_block;
}

elgg_pop_context();
