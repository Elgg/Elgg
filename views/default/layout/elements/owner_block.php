<?php
/**
 * Elgg owner block
 * Displays page ownership information
 *
 * @package Elgg
 * @subpackage Core
 *
 */

elgg_push_context('owner_block');

// groups and other users get owner block
$owner = elgg_get_page_owner();
if ($owner instanceof ElggGroup ||
	($owner instanceof ElggUser && $owner->getGUID() != get_loggedin_userid())) {

	$header = elgg_view_entity($owner, false);

	$body = elgg_view_menu('owner_block', array(
		'entity' => $owner,
		'class' => 'elgg-owner-block-menu',
	));

	$body .= elgg_view('layout/elements/owner_block/extend', $vars);

	echo elgg_view('layout/objects/module', array(
		'header' => $header,
		'body' => $body,
		'class' => 'elgg-owner-block',
	));
}

elgg_pop_context();