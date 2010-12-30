<?php
/**
 * File renderer.
 *
 * @package ElggFile
 */

$full = elgg_get_array_value('full', $vars, FALSE);
$file = elgg_get_array_value('entity', $vars, FALSE);

if (!$file) {
	return TRUE;
}

$owner = $file->getOwnerEntity();
$container = $file->getContainerEntity();
$categories = elgg_view('categories/view', $vars);
$excerpt = elgg_get_excerpt($file->description);
$mime = $file->mimetype;
$base_type = substr($mime, 0, strpos($mime,'/'));

$body = elgg_view('output/longtext', array('value' => $file->description));

$owner_link = elgg_view('output/url', array(
	'href' => "pg/file/owner/$owner->username",
	'text' => $owner->name,
));
$author_text = elgg_echo('blog:author_by_line', array($owner_link));

$file_icon = elgg_view('file/icon', array(
	'mimetype' => $mime,
	'thumbnail' => $file->thumbnail,
	'file_guid' => $file->guid,
	'size' => 'small'
));

if ($file->tags) {
	$tags = "<p class=\"elgg-tags\">" . elgg_view('output/tags', array('tags' => $file->tags)) . "</p>";
} else {
	$tags = "";
}
$date = elgg_view_friendly_time($file->time_created);

$comments_count = elgg_count_comments($file);
//only display if there are commments
if ($comments_count != 0) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', array(
		'href' => $file->getURL() . '#file-comments',
		'text' => $text,
	));
} else {
	$comments_link = '';
}

$metadata = elgg_view('layout/objects/list/metadata', array(
	'entity' => $file,
	'handler' => 'file',
));

$subtitle = "$author_text $date $categories $comments_link";

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}

if ($full && !elgg_in_context('gallery')) {

	$extra = '';
	if (elgg_view_exists("file/specialcontent/$mime")) {
		$extra = elgg_view("file/specialcontent/$mime", $vars);
	} else if (elgg_view_exists("file/specialcontent/$base_type/default")) {
		$extra = elgg_view("file/specialcontent/$base_type/default", $vars);
	}

	$download = elgg_view('output/url', array(
		'href' => "mod/file/download.php?file_guid=$file->guid",
		'text' => elgg_echo("file:download"),
		'class' => 'elgg-action-button',
	));

	$header = elgg_view_title($file->title);

	$params = array(
		'entity' => $file,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);
	$list_body = elgg_view('layout/objects/list/body', $params);

	$file_info = elgg_view_image_block($file_icon, $list_body);

	echo <<<HTML
$header
$file_info
<div class="file elgg-content">
	$body
	$extra
	<p>$download</p>
</div>
HTML;

} elseif (elgg_in_context('gallery')) {
	echo '<div class="file-gallery-item">';
	echo "<h3>" . $file->title . "</h3>";
	echo "<a href=\"{$file->getURL()}\"><img src=\"".elgg_get_site_url()."mod/file/thumbnail.php?size=medium&file_guid={$vars['entity']->getGUID()}\" /></a>";
	echo "<p class='subtitle'>$owner_link $date</p>";
	echo '</div>';
} else {
	// brief view

	$params = array(
		'entity' => $file,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
		'content' => $excerpt,
	);
	$list_body = elgg_view('layout/objects/list/body', $params);

	echo elgg_view_image_block($file_icon, $list_body);
}
