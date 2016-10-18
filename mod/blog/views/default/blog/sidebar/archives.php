<?php
/**
 * Blog archives
 */

$loggedin_user = elgg_get_logged_in_user_entity();
$page_owner = elgg_get_page_owner_entity();
$page = elgg_extract('page', $vars);

if (!$page_owner) {
	return;
}

if ($page == 'friends') {
	return;
}

$content = elgg_view_menu('blog_archive', ['class' => 'elgg-menu-page']);

echo elgg_view_module('aside', elgg_echo('blog:archives'), $content);
