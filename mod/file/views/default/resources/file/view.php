<?php
/**
 * View a file
 *
 * @package ElggFile
 */

$guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'object', 'file');

$file = get_entity($guid);

$owner = elgg_get_page_owner_entity();

elgg_group_gatekeeper();

elgg_push_breadcrumb(elgg_echo('file'), 'file/all');

$crumbs_title = $owner->getDisplayName();
if ($owner instanceof ElggGroup) {
	elgg_push_breadcrumb($crumbs_title, "file/group/$owner->guid/all");
} else {
	elgg_push_breadcrumb($crumbs_title, "file/owner/$owner->username");
}

$title = $file->getDisplayName();

$content = elgg_view_entity($file, [
	'full_view' => true,
	'show_responses' => true,
]);

if ($file->canDownload()) {
	elgg_register_menu_item('title', [
		'name' => 'download',
		'text' => elgg_echo('download'),
		'href' => $file->getDownloadURL(),
		'link_class' => 'elgg-button elgg-button-action',
	]);
}

$body = elgg_view_layout('content', [
	'content' => $content,
	'title' => $title,
	'filter' => '',
]);

echo elgg_view_page($title, $body);
