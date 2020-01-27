<?php
/**
 * Edit profile page
 */

$username = elgg_extract('username', $vars);
$user = get_user_by_username($username);

if (!$user instanceof ElggUser) {
	throw new \Elgg\EntityNotFoundException(elgg_echo("profile:notfound"));
}

// check if logged in user can edit this profile
if (!$user->canEdit()) {
	throw new \Elgg\EntityPermissionsException(elgg_echo("profile:noaccess"));
}

elgg_set_page_owner_guid($user->guid);

elgg_push_context('settings');
elgg_push_context('profile_edit');

echo elgg_view_page(elgg_echo('profile:edit'), [
	'content' => elgg_view_form('profile/edit', [], ['entity' => $user]),
	'show_owner_block_menu' => false,
]);
