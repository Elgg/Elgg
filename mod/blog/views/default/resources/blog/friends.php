<?php

use Elgg\Exceptions\Http\EntityNotFoundException;

$username = elgg_extract('username', $vars);
$lower = elgg_extract('lower', $vars);
$upper = elgg_extract('upper', $vars);

$user = get_user_by_username($username);
if (!$user) {
	throw new EntityNotFoundException();
}

elgg_push_collection_breadcrumbs('object', 'blog', $user, true);

elgg_register_title_button('blog', 'add', 'object', 'blog');

$title = elgg_echo('collection:object:blog:friends');
if ($lower) {
	$title .= ': ' . elgg_echo('date:month:' . date('m', $lower), [date('Y', $lower)]);
}

$content = elgg_view('blog/listing/friends', [
	'entity' => $user,
	'created_after' => $lower,
	'created_before' => $upper,
]);

echo elgg_view_page($title, [
	'content' => $content,
	'sidebar' => elgg_view('blog/sidebar', [
		'page' => 'friends',
		'entity' => $user,
	]),
	'filter_value' => $user->guid === elgg_get_logged_in_user_guid() ? 'friends' : 'none',
]);
