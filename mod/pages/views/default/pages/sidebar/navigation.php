<?php
/**
 * Navigation menu for a user's or a group's pages
 */

$title = elgg_echo('pages:navigation');

pages_register_navigation_tree(elgg_get_page_owner());

$content = elgg_view_menu('pages_nav', array('class' => 'pages-nav'));

echo elgg_view('layout/objects/module', array(
	'title' => $title,
	'body' => $content,
	'class' => 'elgg-aside-module',
));
