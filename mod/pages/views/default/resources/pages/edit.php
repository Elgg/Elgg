<?php
/**
 * Edit a page
 */

elgg_gatekeeper();

$page_guid = (int) elgg_extract('guid', $vars);

elgg_entity_gatekeeper($page_guid, 'object', 'page');

$page = get_entity($page_guid);
if (!$page->canEdit()) {
	register_error(elgg_echo('noaccess'));
	forward('', '403');
}

$container = $page->getContainerEntity();
if (!$container) {
	register_error(elgg_echo('noaccess'));
	forward('', '403');
}

elgg_set_page_owner_guid($container->guid);

if ($container instanceof ElggUser) {
	elgg_push_breadcrumb($container->getDisplayName(), "pages/owner/{$container->username}");
} else if ($container instanceof ElggGroup) {
	elgg_push_breadcrumb($container->getDisplayName(), "pages/group/{$container->guid}");
}


pages_prepare_parent_breadcrumbs($page);
elgg_push_breadcrumb($page->getDisplayName(), $page->getURL());

elgg_push_breadcrumb(elgg_echo('edit'));

$title = elgg_echo('pages:edit');

$vars = pages_prepare_form_vars($page, $page->getParentGUID());
$content = elgg_view_form('pages/edit', [], $vars);

$body = elgg_view_layout('content', [
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('pages/sidebar/navigation', [
		'page' => $page,
	]),
]);

echo elgg_view_page($title, $body);
