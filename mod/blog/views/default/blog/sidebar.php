<?php
/**
 * Blog sidebar
 *
 * @package Blog
 */

// fetch & display latest comments
if ($vars['page'] == 'all') {
	echo elgg_view_latest_comments(0, 'object', 'blog');
} elseif ($vars['page'] == 'owner') {
	// @todo - what we want is the latest comments on this user's blog posts - elgg does not support this
	echo elgg_view_latest_comments(elgg_get_page_owner_guid(), 'object', 'blog');
}

echo elgg_view('blog/sidebar/archives', $vars);

echo elgg_view('blog/sidebar/tagcloud', $vars);
