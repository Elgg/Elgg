<?php
/**
 * Elgg user account settings.
 */

/* @var $user \ElggUser */
$user = elgg_get_page_owner_entity();

echo elgg_view_page(elgg_echo('usersettings:user', [$user->getDisplayName()]), [
	'content' => elgg_view('core/settings/account', [
		'entity' => $user,
	]),
	'show_owner_block_menu' => false,
	'filter_id' => 'settings',
	'filter_value' => 'account',
]);
