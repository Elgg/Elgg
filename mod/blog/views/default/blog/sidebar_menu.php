<?php
/**
 * Blog sidebar menu.
 *
 * @package Blog
 */

// a few cases to consider:
// 1. looking at all posts
// 2. looking at a user's post
// 3. looking at your posts

/*
Logged in or not doesn't matter unless you're looking at your blog.
	Does it matter then on the side bar?

All blogs:
	Archives

Owned blogs;
	Archives
*/

$loggedin_user = get_loggedin_user();
$page_owner = elgg_get_page_owner();

// include a view for plugins to extend
echo elgg_view("blogs/sidebar", array("object_type" => 'blog'));

// fetch & display latest comments on all blog posts
$comments = get_annotations(0, "object", "blog", "generic_comment", "", 0, 4, 0, "desc");
$title = elgg_echo('generic_comments:latest');
$body = elgg_view('comments/latest', array('comments' => $comments));
echo elgg_view('layout/objects/module', array('title' => $title, 'body' => $body));

// only show archives for users or groups.
// This is a limitation of the URL schema.
if ($page_owner && $vars['page'] != 'friends') {
	$dates = blog_get_blog_months($user);

	if ($dates) {
		$title = elgg_echo('blog:archives');
		$content = '<ul class="blog-archives">';
		foreach($dates as $date) {
			$date = $date->yearmonth;

			$timestamplow = mktime(0, 0, 0, substr($date,4,2) , 1, substr($date, 0, 4));
			$timestamphigh = mktime(0, 0, 0, ((int) substr($date, 4, 2)) + 1, 1, substr($date, 0, 4));

			$link = elgg_get_site_url() . 'pg/blog/archive/' . $page_owner->username . '/' . $timestamplow . '/' . $timestamphigh;
			$month = elgg_echo('date:month:' . substr($date, 4, 2), array(substr($date, 0, 4)));
			$content .= "<li><a href=\"$link\" title=\"$month\">$month</a></li>";
		}
		$content .= '</ul>';

		echo elgg_view('layout/objects/module', array('title' => $title, 'body' => $content));
	}

	// friends page lists all tags; mine lists owner's
	$owner_guid = ($vars['page'] == 'friends') ? '' : $page_owner->getGUID();
	$options = array(
		'type' => 'object',
		'subtype' => 'blog',
		'owner_guid' => $owner_guid,
		'threshold' => 0,
		'limit' => 50,
		'tag_name' => 'tags',
	);
	echo elgg_view_tagcloud($options);
} else {
	$options = array(
		'type' => 'object',
		'subtype' => 'blog',
		'threshold' => 0,
		'limit' => 50,
		'tag_name' => 'tags',
	);
	echo elgg_view_tagcloud($options);
}
