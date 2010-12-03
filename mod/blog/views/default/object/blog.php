<?php
/**
 * View for blog objects
 *
 * @package Blog
 */

$full = (isset($vars['full'])) ? $vars['full'] : FALSE;
$blog = (isset($vars['entity'])) ? $vars['entity'] : FALSE;

if (!$blog) {
	return '';
}

$owner = get_entity($blog->owner_guid);
$container = get_entity($blog->container_guid);
$linked_title = "<a href=\"{$blog->getURL()}\" title=\"" . htmlentities($blog->title) . "\">{$blog->title}</a>";
$categories = elgg_view('categories/view', $vars);
$excerpt = $blog->excerpt;

$body = autop($blog->description);
$owner_icon = elgg_view('profile/icon', array('entity' => $owner, 'size' => 'tiny'));
$owner_blog_link = "<a href=\"".elgg_get_site_url()."pg/blog/$owner->username\">{$owner->name}</a>";
$author_text = elgg_echo('blog:author_by_line', array($owner_blog_link));
if($blog->tags){
	$tags = "<p class=\"tags\">" . elgg_view('output/tags', array('tags' => $blog->tags)) . "</p>";
}else{
	$tags = "";
}
$date = elgg_view_friendly_time($blog->publish_date);

// The "on" status changes for comments, so best to check for !Off
if ($blog->comments_on != 'Off') {
	$comments_count = elgg_count_comments($blog);
	//only display if there are commments
	if($comments_count != 0){
		$comments_link = "<a href=\"{$blog->getURL()}#annotations\">" . elgg_echo("comments") . " (". $comments_count .")</a>";
	}else{
		$comments_link = '';
	}
} else {
	$comments_link = '';
}

// links to delete or edit.

// access is always shown.
$edit = elgg_view('output/access', array('entity' => $vars['entity']));

if ($blog->canEdit()) {
	$edit_url = elgg_get_site_url()."pg/blog/{$owner->username}/edit/{$blog->getGUID()}/";
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
			elgg_view_likes($blog) . // include likes
			$edit;

if ($full) {

echo <<<___END
<div class="blogpost clearfix">
	<div id="content-header" class="clearfix">
		<div class="content-header-title"><h2>{$blog->title}</h2></div>
	</div>
	<div class="clearfix">
	<div class="entity-listing-icon">
		$owner_icon
	</div>
	<div class="entity-listing-info">
		<div class="entity-metadata">$edit</div>
		<p class="entity-subtext">
			$author_text
			$date
			$categories
			$comments_link
		</p>
		$tags
	</div>
	</div>
	<div class='blog_post'>$body</div>
</div>

___END;

} else {
	echo <<<___END
<div class="blog $status_class entity-listing clearfix">
	<div class="entity-listing-icon">
		$owner_icon
	</div>
	<div class="entity-listing-info">
		<div class="entity-metadata">$edit</div>
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
</div>

___END;
}
