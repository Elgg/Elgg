<?php
/**
 * Blog archives
 */

$content = elgg_view_menu('blog_archive', [
	'page' => elgg_extract('page', $vars),
	'entity' => elgg_extract('entity', $vars, elgg_get_page_owner_entity()),
	'class' => 'elgg-menu-page',
	'show_blog_archive' => elgg_extract('show_blog_archive', $vars),
	'blog_archive_options' => elgg_extract('blog_archive_options', $vars),
	'blog_archive_url' => elgg_extract('blog_archive_url', $vars),
]);

if (!$content) {
	return;
}

echo elgg_view_module('aside', elgg_echo('blog:archives'), $content);
