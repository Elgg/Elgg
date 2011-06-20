<?php
/**
 * View a file
 *
 * @package ElggFile
 */

$file = get_entity(get_input('guid'));

$owner = elgg_get_page_owner_entity();

elgg_push_breadcrumb(elgg_echo('file'), 'file/all');

$crumbs_title = $owner->name;
if (elgg_instanceof($owner, 'group')) {
	elgg_push_breadcrumb($crumbs_title, "file/group/$owner->guid/all");
} else {
	elgg_push_breadcrumb($crumbs_title, "file/owner/$owner->username");
}

$title = $file->title;

elgg_push_breadcrumb($title);

$content = elgg_view_entity($file, true);
$content .= elgg_view_comments($file);

$download = elgg_view('output/url', array(
	'href' => "mod/file/download.php?file_guid=$file->guid",
	'text' => elgg_echo("file:download"),
	'class' => 'elgg-button elgg-button-action float-alt',
));

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
	'buttons' => $download,
));

echo elgg_view_page($title, $body);
