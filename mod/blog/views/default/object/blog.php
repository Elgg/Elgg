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
$edit = elgg_view('output/access', array('entity' => $vars['entity']));

// links to delete or edit.
if ($blog->canEdit()) {
	$edit_url = elgg_get_site_url()."pg/blog/edit/{$owner->username}/{$blog->getGUID()}/";
	$edit_link = "<span class='entity-edit'><a href=\"$edit_url\">" . elgg_echo('edit') . '</a></span>';

	$delete_url = "action/blog/delete?guid={$blog->getGUID()}";
	$delete_link = "<span class='delete-button'>" . elgg_view('output/confirmlink', array(
		'href' => $delete_url,
		'text' => elgg_echo('delete'),
		'confirm' => elgg_echo('deleteconfirm')
	)) . "</span>";

	$status = '';
	if ($blog->status != 'published') {
		$status_text = elgg_echo("blog:status:{$blog->status}");
		$status = "<span class='blog_status'>$status_text</span>";
	}

	$edit .= "$status $edit_link $delete_link";
}

// include a view for plugins to extend
$edit = elgg_view("blogs/options", array("object_type" => 'blog', 'entity' => $blog)) .
			elgg_view_likes($blog) . $edit;

if ($full) {

	$params = array(
		'title' => $blog->title,
		'buttons' => '',
	);
	$header = elgg_view('content/header', $params);

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

	$params = array(
		'img' => $owner_icon,
		'body' => $info,
	);
	$blog_info = elgg_view('layout_elements/media', $params);

echo <<<HTML
$header
$blog_info
<div class="blog_post elgg-content">
	$body
</div>
HTML;

} else {
	// brief view

	$body = <<<HTML
	<div class="entity-listing-info">
		<div class="elgg-metadata">$edit</div>
		<p class="entity-title">$linked_title</p>
		<p class="entity-subtext">
			$author_text
			$date
			$categories
			$comments_link
		</p>
		$tags
		<p>$excerpt</p>
	</div>
HTML;

	$params = array(
		'img' => $owner_icon,
		'body' => $body,
	);
	echo elgg_view('layout_elements/media', $params);
}
