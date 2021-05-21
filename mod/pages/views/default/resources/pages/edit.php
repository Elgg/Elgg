<?php
/**
 * Edit a page
 */

$page_guid = (int) elgg_extract('guid', $vars);

elgg_entity_gatekeeper($page_guid, 'object', 'page', true);

$page = get_entity($page_guid);

$container = $page->getContainerEntity();
if ($container) {
	elgg_push_collection_breadcrumbs('object', 'page', $container);
}

pages_prepare_parent_breadcrumbs($page);
elgg_push_breadcrumb($page->getDisplayName(), $page->getURL());

$vars = pages_prepare_form_vars($page, $page->getParentGUID());

echo elgg_view_page(elgg_echo('edit:object:page'), [
	'content' => elgg_view_form('pages/edit', [], $vars),
	'filter_id' => 'pages/edit',
]);
