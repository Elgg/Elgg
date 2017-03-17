<?php
/**
 * Create a new collection
 */

elgg_gatekeeper();

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

$title = elgg_echo('friends:collections:add');

elgg_push_breadcrumb($user->getDisplayName(), $user->getURL());
elgg_push_breadcrumb(elgg_echo('friends'), 'friends');
elgg_push_breadcrumb(elgg_echo('friends:collections'), 'collections');
elgg_push_breadcrumb($title);

$form_name = 'friends/collections/edit';
$form_vars = [];
if (elgg_is_sticky_form($form_name)) {
	$form_vars = elgg_get_sticky_values($form_name);
	elgg_clear_sticky_form($form_name);
}

$content = elgg_view_form($form_name, [], $form_vars);

$body = elgg_view_layout('one_sidebar', [
	'title' => $title,
	'content' => $content
]);

echo elgg_view_page($title, $body);
