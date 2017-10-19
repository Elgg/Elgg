<?php
/**
 * Delete blog entity
 *
 * @package Blog
 */

$blog_guid = (int) get_input('guid');
$blog = get_entity($blog_guid);

if (!($blog instanceof \ElggBlog) || !$blog->canDelete()) {
	return elgg_error_response(elgg_echo('blog:error:post_not_found'));
}

$container = $blog->getContainerEntity();
if (!$blog->delete()) {
	return elgg_error_response(elgg_echo('blog:error:cannot_delete_post'));
}

if ($container instanceof \ElggGroup) {
	$forward_url = "blog/group/{$container->guid}/all";
} else {
	$forward_url = "blog/owner/{$container->username}";
}

return elgg_ok_response('', elgg_echo('blog:message:deleted_post'), $forward_url);
