<?php

/**
 * Displays a list of user's friends collections
 *
 * @uses $vars['username'] Collection owner username
 *                         Defaults to logged in user
 *
 */

use Elgg\Exceptions\Http\EntityNotFoundException;

$username = elgg_extract('username', $vars);
if ($username) {
	$user = get_user_by_username($username);
} else {
	$user = elgg_get_logged_in_user_entity();
}

if (!$user instanceof ElggUser || !$user->canEdit()) {
	throw new EntityNotFoundException();
}

elgg_set_page_owner_guid($user->guid);

elgg_push_breadcrumb($user->getDisplayName(), $user->getURL());
elgg_push_breadcrumb(elgg_echo('friends'), elgg_generate_url('collection:friends:owner', ['username' => $user->username]));

elgg_register_menu_item('title', [
	'name' => 'add',
	'icon' => 'plus',
	'href' => elgg_generate_url('add:access_collection:friends', [
		'username' => $user->username,
	]),
	'text' => elgg_echo('friends:collections:add'),
	'link_class' => 'elgg-button elgg-button-action',
]);

echo elgg_view_page(elgg_echo('friends:collections'), [
	'content' => elgg_view('collections/listing/owner', [
		'entity' => $user,
	]),
	'show_owner_block_menu' => false,
	'filter_id' => 'friends_collections',
]);
