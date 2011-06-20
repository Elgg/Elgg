<?php
/**
 * File renderer.
 *
 * @package ElggFile
 */

$full = elgg_extract('full_view', $vars, FALSE);
$file = elgg_extract('entity', $vars, FALSE);

if (!$file) {
	return TRUE;
}

$owner = $file->getOwnerEntity();
$container = $file->getContainerEntity();
$categories = elgg_view('output/categories', $vars);
$excerpt = elgg_get_excerpt($file->description);
$mime = $file->mimetype;
$base_type = substr($mime, 0, strpos($mime,'/'));

$body = elgg_view('output/longtext', array('value' => $file->description));

$owner_link = elgg_view('output/url', array(
	'href' => "file/owner/$owner->username",
	'text' => $owner->name,
));
$author_text = elgg_echo('byline', array($owner_link));

$file_icon = elgg_view_entity_icon($file, 'small');

$tags = elgg_view('output/tags', array('tags' => $file->tags));
$date = elgg_view_friendly_time($file->time_created);

$comments_count = $file->countComments();
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

$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'file',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
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

	$header = elgg_view_title($file->title);

	$params = array(
		'entity' => $file,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);
	$list_body = elgg_view('object/elements/summary', $params);

	$file_info = elgg_view_image_block($file_icon, $list_body);

	echo <<<HTML
$file_info
<div class="file elgg-content">
	$body
	$extra
</div>
HTML;

} elseif (elgg_in_context('gallery')) {
	echo '<div class="file-gallery-item">';
	echo "<h3>" . $file->title . "</h3>";
	echo elgg_view_entity_icon($file, 'medium');
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
	$list_body = elgg_view('object/elements/summary', $params);

	echo elgg_view_image_block($file_icon, $list_body);
}
