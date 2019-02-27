<?php
/**
 * Create a new collection
 */

$username = elgg_extract('username', $vars);
if ($username) {
	$user = get_user_by_username($username);
} else {
	$user = elgg_get_logged_in_user_entity();
}

if (!$user instanceof ElggUser || !$user->canEdit()) {
	throw new \Elgg\EntityNotFoundException();
}

elgg_set_page_owner_guid($user->guid);

$title = elgg_echo('friends:collections:add');

elgg_push_breadcrumb($user->getDisplayName(), $user->getURL());
elgg_push_breadcrumb(elgg_echo('friends'), "friends/{$user->username}");
elgg_push_breadcrumb(elgg_echo('friends:collections'), "friends/collections/owner/{$user->username}");

$form_name = 'friends/collections/edit';
$form_vars = [];
if (elgg_is_sticky_form($form_name)) {
	$form_vars = elgg_get_sticky_values($form_name);
	elgg_clear_sticky_form($form_name);
}

$content = elgg_view_form($form_name, [], $form_vars);

$body = elgg_view_layout('one_sidebar', [
	'title' => $title,
	'content' => $content,
	'show_owner_block_menu' => false,
]);

echo elgg_view_page($title, $body);
