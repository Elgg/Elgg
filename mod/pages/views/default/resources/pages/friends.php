<?php
/**
 * List a user's friends' pages
 */

use Elgg\Exceptions\Http\EntityNotFoundException;

$username = (string) elgg_extract('username', $vars);

$owner = elgg_get_user_by_username($username);
if (!$owner instanceof \ElggUser) {
	throw new EntityNotFoundException();
}

elgg_push_collection_breadcrumbs('object', 'page', $owner, true);

elgg_register_title_button('add', 'object', 'page');

$title = elgg_echo('collection:object:page:friends');

$content = elgg_view('pages/listing/friends', [
	'entity' => $owner,
]);

echo elgg_view_page($title, [
	'filter_value' => $owner->guid === elgg_get_logged_in_user_guid() ? 'friends' : 'none',
	'content' => $content,
]);
