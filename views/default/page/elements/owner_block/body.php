<?php

/**
 * Owner block body
 *
 * @uses $vars['owner_block_owner']  Override owner block owner
 */
$owner = elgg_extract('owner_block_owner', $vars);
if (!isset($owner)) {
	$owner = elgg_get_page_owner_entity();
}

if (!$owner instanceof ElggGroup && !$owner instanceof ElggUser) {
	return;
}

echo elgg_view_menu('owner_block', [
	'entity' => $owner,
	'sort_by' => 'priority',
	'class' => 'elgg-menu-page flex-column list-group list-group-flush',
	'item_class' => 'list-group-item',
]);

echo elgg_view('page/elements/owner_block/extend', $vars);
