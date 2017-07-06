<?php

/**
 * Displays a list of user's friends collections
 *
 * @uses $vars['username'] Collection owner username
 *                         Defaults to logged in user
 *
 */

$username = elgg_extract('username', $vars);
if ($username) {
	$user = get_user_by_username($username);
} else {
	$user = elgg_get_logged_in_user_entity();
}

if (!$user instanceof ElggUser || !$user->canEdit()) {
	forward('', '404');
}

elgg_set_page_owner_guid($user->guid);

$title = elgg_echo('friends:collections');

elgg_push_breadcrumb($user->getDisplayName(), $user->getURL());
elgg_push_breadcrumb(elgg_echo('friends'), 'friends');
elgg_push_breadcrumb($title);

elgg_register_menu_item('title', [
	'name' => 'add',
	'href' => 'collections/add',
	'text' => elgg_echo('friends:collections:add'),
	'link_class' => 'elgg-button elgg-button-action',
]);

$content = elgg_view('collections/listing/owner', [
	'entity' => $user,
]);

$body = elgg_view_layout('one_sidebar', [
	'content' => $content,
	'title' => $title,
]);

echo elgg_view_page($title, $body);
