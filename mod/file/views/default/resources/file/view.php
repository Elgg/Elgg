<?php
/**
 * View a file
 */

$guid = (int) elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'object', 'file');

/* @var $file \ElggFile */
$file = get_entity($guid);

elgg_push_entity_breadcrumbs($file, false);

if ($file->canDownload()) {
	elgg_register_menu_item('title', [
		'name' => 'download',
		'text' => elgg_echo('download'),
		'href' => $file->getDownloadURL(),
		'icon' => 'download',
		'link_class' => 'elgg-button elgg-button-action',
	]);
}

echo elgg_view_page($file->getDisplayName(), [
	'content' => elgg_view_entity($file, [
		'full_view' => true,
		'show_responses' => true,
	]),
	'entity' => $file,
	'filter_id' => 'file/view',
]);
