<?php
/**
 * Elgg user statistics.
 */

use Elgg\Exceptions\Http\EntityPermissionsException;

$user = elgg_get_page_owner_entity();
if (!$user instanceof \ElggUser) {
	$user = elgg_get_logged_in_user_entity();
	elgg_set_page_owner_guid($user->guid);
}

if (!$user->canEdit()) {
	throw new EntityPermissionsException();
}

elgg_push_breadcrumb(elgg_echo('settings'), elgg_generate_url('settings:account', [
	'username' => $user->username,
]));

echo elgg_view_page(elgg_echo('usersettings:statistics'), [
	'content' => elgg_view('core/settings/statistics'),
	'show_owner_block_menu' => false,
	'filter_id' => 'settings',
	'filter_value' => 'statistics',
]);
