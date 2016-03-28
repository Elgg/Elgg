<?php
/**
 * Elgg custom index page
 * 
 * You can edit the content of this page with your own layout and style. 
 * Whatever you put in this view will appear on the front page of your site.
 * 
 */

elgg_push_context('front');

elgg_push_context('widgets');

$list_params = array(
	'type' => 'object',
	'limit' => 4,
	'full_view' => false,
	'list_type_toggle' => false,
	'pagination' => false,
);

//grab the latest 4 blog posts
$list_params['subtype'] = 'blog';
$blogs = elgg_list_entities($list_params);

//grab the latest bookmarks
$list_params['subtype'] = 'bookmarks';
$bookmarks = elgg_list_entities($list_params);

//grab the latest files
$list_params['subtype'] = 'file';
$files = elgg_list_entities($list_params);

//get the newest members who have an avatar
$newest_members = elgg_list_entities_from_metadata(array(
	'metadata_names' => 'icontime',
	'type' => 'user',
	'limit' => 10,
	'full_view' => false,
	'pagination' => false,
	'list_type' => 'gallery',
	'gallery_class' => 'elgg-gallery-users',
	'size' => 'small',
));

//newest groups
$list_params['type'] = 'group';
unset($list_params['subtype']);
$groups = elgg_list_entities($list_params);

//grab the login form
$login = elgg_view("core/account/login_box");

elgg_pop_context();

// lay out the content
$vars = array(
	'blogs' => $blogs,
	'bookmarks' => $bookmarks,
	'files' => $files,
	'groups' => $groups,
	'login' => $login,
	'members' => $newest_members,
);

$body = elgg_view('custom_index/content', $vars);

// no RSS feed with a "widget" front page
elgg_unregister_rss_link();

echo elgg_view_page('', $body);
