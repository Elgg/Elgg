<?php
/**
 * History of revisions of a page
 *
 * @package ElggPages
 */

$page_guid = elgg_extract('guid', $vars);

$page = get_entity($page_guid);
if (!pages_is_page($page)) {
	forward('', '404');
}

$container = $page->getContainerEntity();
if (!$container) {
	forward('', '404');
}

elgg_set_page_owner_guid($container->getGUID());

if (elgg_instanceof($container, 'group')) {
	elgg_push_breadcrumb($container->name, "pages/group/$container->guid/all");
} else {
	elgg_push_breadcrumb($container->name, "pages/owner/$container->username");
}
pages_prepare_parent_breadcrumbs($page);
elgg_push_breadcrumb($page->title, $page->getURL());
elgg_push_breadcrumb(elgg_echo('pages:history'));

$title = $page->title . ": " . elgg_echo('pages:history');

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
	'sidebar' => elgg_view('pages/sidebar/navigation', ['page' => $page]),
]);

echo elgg_view_page($title, $body);
