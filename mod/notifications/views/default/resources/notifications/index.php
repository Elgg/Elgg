<?php
/**
 * Elgg notifications plugin index
 *
 * @uses $user ElggUser
 */

$user = elgg_get_page_owner_entity();

// Set the context to settings
elgg_set_context('settings');

elgg_push_breadcrumb(elgg_echo('settings'), elgg_generate_url('settings:account', ['username' => $user->username]));

echo elgg_view_page(elgg_echo('notifications:subscriptions:changesettings'), [
	'content' => elgg_view('notifications/settings', [
		'user' => $user,
	]),
	'show_owner_block_menu' => false,
]);
