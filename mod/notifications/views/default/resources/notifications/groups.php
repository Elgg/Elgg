<?php
/**
 * Elgg notifications plugin group index
 *
 * @uses $user ElggUser
 */

$user = elgg_get_page_owner_entity();

// Set the context to settings
elgg_set_context('settings');

elgg_push_breadcrumb(elgg_echo('settings'), elgg_generate_url('settings:account', ['username' => $user->username]));

echo elgg_view_page(elgg_echo('notifications:subscriptions:groups:title'), [
	'content' => elgg_view_form('notifications/subscriptions/groups', [
		'action' => elgg_normalize_url('action/notifications/subscriptions'),
		'class' => 'elgg-subscription-module',
	], [
		'user' => $user,
	]),
	'show_owner_block_menu' => false,
]);
