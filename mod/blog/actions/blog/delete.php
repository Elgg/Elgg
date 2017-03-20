<?php

/**
 * Delete blog entity
 *
 * @package Blog
 */
$blog_guid = get_input('guid');
$blog = get_entity($blog_guid);

if (!elgg_instanceof($blog, 'object', 'blog') || !$blog->canEdit()) {
	return elgg_error_response(elgg_echo('blog:error:post_not_found'));
}

$data = [
	'deleted' => (array) $blog->toObject(),
];
$container = get_entity($blog->container_guid);

if (!$blog->delete()) {
	return elgg_error_response(elgg_echo('blog:error:cannot_delete_post'));
}

if (elgg_instanceof($container, 'group')) {
	$forward_url = elgg_generate_url('blog_group', [
		'group_guid' => $container->guid,
		'subpage' => 'all',
	]);
} else {
	$foward_url = elgg_generate_url('blog_owner', [
		'username' => $container->username,
	]);
}

$message = elgg_echo('blog:message:deleted_post');
return elgg_ok_response($data, $message, $forward_url);
