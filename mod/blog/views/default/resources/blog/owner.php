<?php

use Elgg\Exceptions\Http\EntityNotFoundException;

$username = elgg_extract('username', $vars);
$lower = elgg_extract('lower', $vars);
$upper = elgg_extract('upper', $vars);

$user = get_user_by_username($username);
if (!$user) {
	throw new EntityNotFoundException();
}

elgg_register_title_button('blog', 'add', 'object', 'blog');

elgg_push_collection_breadcrumbs('object', 'blog', $user);

if ($user->guid === elgg_get_logged_in_user_guid()) {
	$title = elgg_echo('collection:object:blog');
} else {
	$title = elgg_echo('collection:object:blog:owner', [$user->getDisplayName()]);
}

if ($lower) {
	$title .= ': ' . elgg_echo('date:month:' . date('m', $lower), [date('Y', $lower)]);
}

$content = elgg_view('blog/listing/owner', [
	'entity' => $user,
	'created_after' => $lower,
	'created_before' => $upper,
]);

echo elgg_view_page($title, [
	'content' => $content,
	'sidebar' => elgg_view('blog/sidebar', [
		'page' => 'owner',
		'entity' => $user,
	]),
	'filter_value' => $user->guid === elgg_get_logged_in_user_guid() ? 'mine' : 'none',
]);
