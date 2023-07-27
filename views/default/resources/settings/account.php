<?php
/**
 * Elgg user account settings.
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

$title = elgg_echo('usersettings:user', [$user->getDisplayName()]);

echo elgg_view_page($title, [
	'content' => elgg_view('core/settings/account', [
		'entity' => $user,
	]),
	'show_owner_block_menu' => false,
	'filter_id' => 'settings',
	'filter_value' => 'account',
]);
