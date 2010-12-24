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

$owner = get_entity($blog->owner_guid);
$container = get_entity($blog->container_guid);
$linked_title = "<a href=\"{$blog->getURL()}\" title=\"" . htmlentities($blog->title) . "\">{$blog->title}</a>";
$categories = elgg_view('categories/view', $vars);
$excerpt = $blog->excerpt;

$body = autop($blog->description);
$owner_icon = elgg_view('profile/icon', array('entity' => $owner, 'size' => 'tiny'));
$owner_blog_link = "<a href=\"" . elgg_get_site_url() . "pg/blog/owner/$owner->username\">{$owner->name}</a>";
$author_text = elgg_echo('blog:author_by_line', array($owner_blog_link));
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
		$comments_link = "<a href=\"{$blog->getURL()}#annotations\">$text</a>";
	} else {
		$comments_link = '';
	}
} else {
	$comments_link = '';
}

// access is always shown.
$metadata = '<ul class="elgg-list-metadata">';
$metadata .= '<li>' . elgg_view('output/access', array('entity' => $vars['entity'])) . '</li>';

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

	$edit_url = elgg_get_site_url()."pg/blog/edit/{$owner->username}/{$blog->getGUID()}/";
	$edit_link = "<span class='entity-edit'><a href=\"$edit_url\">" . elgg_echo('edit') . '</a></span>';
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

	$params = array(
		'title' => $blog->title,
		'buttons' => '',
	);
	$header = elgg_view('content/header', $params);

	$params = array(
		'entity' => $blog,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);
	$list_body = elgg_view('layout/objects/list/body', $params);

	$info = <<<HTML
<div class="entity-listing-info clearfix">
	<div class="entity-metadata">$edit</div>
	<p class="entity-subtext">
		$author_text
		$date
		$categories
		$comments_link
	</p>
	$tags
</div>
HTML;

	$blog_info = elgg_view_image_block($owner_icon, $list_body);

	echo <<<HTML
$header
$blog_info
<div class="blog_post elgg-content">
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
