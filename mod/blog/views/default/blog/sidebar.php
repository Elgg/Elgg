<?php
/**
 * Blog sidebar
 *
 * @package Blog
 */

// fetch & display latest comments
if ($vars['page'] != 'friends') {
	echo elgg_view('page/elements/comments_block', array(
		'subtypes' => 'blog',
		'container_guid' => elgg_get_page_owner_guid(),
	));
}

// only users can have archives at present
if ($vars['page'] == 'owner' || $vars['page'] == 'group') {
	echo elgg_view('blog/sidebar/archives', $vars);
}

if ($vars['page'] != 'friends') {
	echo elgg_view('page/elements/tagcloud_block', array(
		'subtypes' => 'blog',
		'container_guid' => elgg_get_page_owner_guid(),
	));
}
