<?php

$guid = (int) elgg_extract('guid', $vars);

/** @var \ElggFile $entity */
$entity = elgg_entity_gatekeeper($guid, 'object', 'file');

elgg_push_entity_breadcrumbs($entity);

if ($entity->canDownload()) {
	elgg_register_menu_item('title', [
		'name' => 'download',
		'text' => elgg_echo('download'),
		'href' => $entity->getDownloadURL(),
		'icon' => 'download',
		'link_class' => 'elgg-button elgg-button-action',
	]);
}

echo elgg_view_page($entity->getDisplayName(), [
	'content' => elgg_view_entity($entity),
	'entity' => $entity,
	'filter_id' => 'file/view',
]);
