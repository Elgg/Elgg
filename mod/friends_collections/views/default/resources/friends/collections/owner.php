<?php
/**
 * Displays a list of user's friends collections
 */

$user = elgg_get_page_owner_entity();

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
	'filter_id' => 'friends_collections',
]);
