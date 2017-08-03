<?php

/**
 * Save blog entity
 *
 * Can be called by clicking save button or preview button. If preview button,
 * we automatically save as draft. The preview button is only available for
 * non-published drafts.
 *
 * Drafts are saved with the access set to private.
 *
 * @package Blog
 */
elgg_make_sticky_form('blog');

$user = elgg_get_logged_in_user_entity();

$guid = get_input('guid');
if ($guid) {
	$blog = get_entity($guid);
	if (!$blog instanceof ElggBlog || !$blog->canEdit()) {
		$error = elgg_echo('blog:error:post_not_found');
		return elgg_error_response($error);
	}
} else {
	$blog = new ElggBlog();
}

if (!$blog->guid || $blog->status == ElggBlog::UNSAVED_DRAFT) {
	$container_guid = get_input('container_guid', elgg_get_logged_in_user_guid());
	$container = get_entity($container_guid);
	if (!$container || !$container->canWriteToContainer(0, 'object', 'blog')) {
		$error = elgg_echo('blog:error:cannot_write_to_container');
		return elgg_error_response($error);
	}
} else {
	$container = $blog->getContainerEntity();
}

$title = htmlspecialchars(get_input('title', '', false), ENT_QUOTES, 'UTF-8');
if (empty($title)) {
	$error = elgg_echo('blog:error:missing:title');
	return elgg_error_response($error);
}

$description = get_input('description');
if (empty($description)) {
	$error = elgg_echo('blog:error:missing:description');
	return elgg_error_response($error);
}

$access_id = get_input('access_id', get_default_access());

$blog->container_guid = $container->guid;
$blog->title = $title;
$blog->description = $description;
$blog->tags = string_to_tag_array(get_input('tags', ''));
$blog->excerpt = elgg_get_excerpt(get_input('excerpt', ''));
$blog->comments_on = get_input('comments_on') == 'On' ? 'On' : 'Off';
// When previewing, force status to draft
$blog->previous_status = $blog->status ?: ElggBlog::DRAFT;
$blog->status = (bool) get_input('save') ? get_input('status', ElggBlog::DRAFT) : ElggBlog::DRAFT;

if ($blog->status == ElggBlog::DRAFT) {
	// If draft, set access to private and cache the future access
	$blog->future_access = $access_id;
	$blog->access_id = ACCESS_PRIVATE;
} else {
	$blog->access_id = $access_id;
}

if (!$blog->save()) {
	$error = elgg_echo('blog:error:cannot_save');
	return elgg_error_response($error);
}

elgg_clear_sticky_form('blog');

$data = [
	'entity' => $blog,
];

$forward_url = $blog->getURL();
$message = elgg_echo('blog:message:saved');

return elgg_ok_response($data, $message, $forward_url);
