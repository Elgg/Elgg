<?php
/**
 * History of revisions of a page
 */

$page_guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($page_guid, 'object', 'page');

$page = get_entity($page_guid);

$container = $page->getContainerEntity();
if (!$container) {
	throw new \Elgg\EntityNotFoundException();
}

elgg_set_page_owner_guid($container->getGUID());

elgg_push_breadcrumb(elgg_echo('pages'), 'pages/all');

if ($container instanceof ElggUser) {
	elgg_push_breadcrumb($container->getDisplayName(), "pages/owner/{$container->username}");
} else if ($container instanceof ElggGroup) {
	elgg_push_breadcrumb($container->getDisplayName(), "pages/group/{$container->guid}");
}

pages_prepare_parent_breadcrumbs($page);

elgg_push_breadcrumb($page->getDisplayName(), $page->getURL());
elgg_push_breadcrumb(elgg_echo('pages:history'));

$title = "{$page->getDisplayName()}: " . elgg_echo('pages:history');

$content = elgg_list_annotations([
	'guid' => $page_guid,
	'annotation_name' => 'page',
	'limit' => max(20, elgg_get_config('default_limit')),
	'order_by' => "n_table.time_created desc",
]);

$body = elgg_view_layout('content', [
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('pages/sidebar/navigation', [
		'page' => $page,
	]),
]);

echo elgg_view_page($title, $body);
