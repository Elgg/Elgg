<?php
/**
 * Delete blog entity
 *
 * @package Blog
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

$blog_guid = get_input('guid');
$blog = get_entity($blog_guid);

if (elgg_instanceof($blog, 'object', 'blog') && $blog->canEdit()) {
	if ($blog->delete()) {
		system_message(elgg_echo('blog:message:deleted_post'));
	} else {
		register_error(elgg_echo('blog:error:cannot_delete_post'));
	}
} else {
	register_error(elgg_echo('blog:error:post_not_found'));
}

forward($_SERVER['HTTP_REFERER']);