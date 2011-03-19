<?php
/**
 * List a user's friends' pages
 *
 * @package ElggPages
 */

$owner = elgg_get_page_owner_entity();
if (!$owner) {

}

elgg_push_breadcrumb($owner->name, "pages/owner/$owner->username");
elgg_push_breadcrumb(elgg_echo('friends'));

$title = elgg_echo('pages:friends');

$content = list_user_friends_objects($owner->guid, 'page_top', 10, false);
if (!$content) {
	$content = elgg_echo('pages:none');
}

$params = array(
	'filter_context' => 'friends',
	'content' => $content,
	'title' => $title,
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
