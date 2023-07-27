<?php
/**
 * Edit a page
 */

$page_guid = (int) elgg_extract('guid', $vars);

elgg_entity_gatekeeper($page_guid, 'object', 'page', true);

/* @var $page \ElggPage */
$page = get_entity($page_guid);

elgg_push_collection_breadcrumbs('object', 'page', $page->getContainerEntity());

pages_prepare_parent_breadcrumbs($page);

echo elgg_view_page(elgg_echo('edit:object:page'), [
	'content' => elgg_view_form('pages/edit', ['sticky_enabled' => true], [
		'entity' => $page,
		'parent_guid' => $page->getParentGUID(),
	]),
	'filter_id' => 'pages/edit',
]);
