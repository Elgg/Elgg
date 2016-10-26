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
$categories = elgg_view('output/categories', $vars);

$by_line = elgg_view('page/elements/by_line', $vars);

$comments_link = '';
$comments_count = $file->countComments();
if ($comments_count) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', array(
		'href' => $file->getURL() . '#comments',
		'text' => $text,
		'is_trusted' => true,
	));
}

if (elgg_in_context('gallery')) {
	echo '<div class="file-gallery-item">';
	echo "<h3>" . $file->title . "</h3>";
	echo elgg_view_entity_icon($file, 'medium');
	echo "<p class='subtitle'>$owner_link $date</p>";
	echo '</div>';
	return;
}

$mime = $file->getMimeType();
$base_type = substr($mime, 0, strpos($mime, '/'));

$extra = '';
if (elgg_view_exists("file/specialcontent/$mime")) {
	$extra = elgg_view("file/specialcontent/$mime", $vars);
} else if (elgg_view_exists("file/specialcontent/$base_type/default")) {
	$extra = elgg_view("file/specialcontent/$base_type/default", $vars);
}

$text = elgg_view('output/longtext', array('value' => $file->description));
$body = "$text $extra";

$view = $full ? 'object/elements/full' : 'object/elements/summary';
echo elgg_view($view, [
	'entity' => $file,
	'responses_link' => $comments_link,
	'body' => $body,
]);
