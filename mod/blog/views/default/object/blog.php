<?php
/**
 * View for blog objects
 *
 * @package Blog
 */

$full = elgg_get_array_value('full', $vars, FALSE);
$blog = elgg_get_array_value('entity', $vars, FALSE);

if (!$blog) {
	return TRUE;
}

$owner = $blog->getOwnerEntity();
$container = $blog->getContainerEntity();
$categories = elgg_view('categories/view', $vars);
$excerpt = $blog->excerpt;

$body = autop($blog->description);
$owner_icon = elgg_view('profile/icon', array('entity' => $owner, 'size' => 'tiny'));
$owner_link = elgg_view('output/url', array(
	'href' => "pg/blog/owner/$owner->username",
	'text' => $owner->name,
));
$author_text = elgg_echo('blog:author_by_line', array($owner_link));
if ($blog->tags) {
	$tags = "<p class=\"elgg-tags\">" . elgg_view('output/tags', array('tags' => $blog->tags)) . "</p>";
} else {
	$tags = "";
}
$date = elgg_view_friendly_time($blog->publish_date);

// The "on" status changes for comments, so best to check for !Off
if ($blog->comments_on != 'Off') {
	$comments_count = elgg_count_comments($blog);
	//only display if there are commments
	if ($comments_count != 0) {
		$text = elgg_echo("comments") . " ($comments_count)";
		$comments_link = elgg_view('output/url', array(
			'href' => $blog->getURL() . '#blog-comments',
			'text' => $text,
		));
	} else {
		$comments_link = '';
	}
} else {
	$comments_link = '';
}

// access is always shown.
$metadata = '<ul class="elgg-list-metadata">';
$metadata .= '<li>' . elgg_view('output/access', array('entity' => $blog)) . '</li>';

if (isloggedin() && $blog->getOwnerGUID() != get_loggedin_userid()) {
	$likes = elgg_view_likes($blog);
	$metadata .= "<li>$likes</li>";
}

// pass <li>your data</li> back from the view
$metadata .= elgg_view("entity/metadata", array('entity' => $blog));

// links to delete or edit.
if ($blog->canEdit()) {

	$status = '';
	if ($blog->status != 'published') {
		$status_text = elgg_echo("blog:status:{$blog->status}");
		$metadata .= "<li>$status_text</li>";
	}

	$edit_url = elgg_get_site_url() . "pg/blog/edit/{$owner->username}/{$blog->getGUID()}/";
	$edit_link = elgg_view('output/url', array(
		'href' => $edit_url,
		'text' => elgg_echo('edit'),
	));
	$metadata .= "<li>$edit_link</li>";

	$delete_url = "action/blog/delete?guid={$blog->getGUID()}";
	$delete_link = elgg_view('output/confirmlink', array(
		'href' => $delete_url,
		'text' => '<span class="elgg-icon elgg-icon-delete"></span>',
		'title' => elgg_echo('delete'),
		'confirm' => elgg_echo('deleteconfirm'),
		'encode' => false,
	));
	$metadata .= "<li>$delete_link</li>";
}

$metadata .= '</ul>';

$subtitle = "$author_text $date $categories $comments_link";

if ($full) {

	$header = elgg_view_title($blog->title);

	$params = array(
		'entity' => $blog,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);
	$list_body = elgg_view('layout/objects/list/body', $params);

	$blog_info = elgg_view_image_block($owner_icon, $list_body);

	echo <<<HTML
$header
$blog_info
<div class="blog-post elgg-content">
	$body
</div>
HTML;

} else {
	// brief view

	$params = array(
		'entity' => $blog,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
		'content' => $excerpt,
	);
	$list_body = elgg_view('layout/objects/list/body', $params);

	echo elgg_view_image_block($owner_icon, $list_body);
}
