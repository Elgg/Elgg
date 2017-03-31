<?php
/**
 * Blog sidebar
 *
 * @package Blog
 */

$page = elgg_extract('page', $vars);

if ($page == 'view') {
	return;
}

if (in_array($page, ['owner', 'group', 'archive'])) {
	echo elgg_view('blog/sidebar/archives', $vars);
}

// fetch & display latest comments
if ($page != 'friends') {
	echo elgg_view('page/elements/comments_block', [
		'subtypes' => 'blog',
		'container_guid' => elgg_get_page_owner_guid(),
	]);
}

if ($page != 'friends') {
	echo elgg_view('page/elements/tagcloud_block', [
		'subtypes' => 'blog',
		'container_guid' => elgg_get_page_owner_guid(),
	]);
}
