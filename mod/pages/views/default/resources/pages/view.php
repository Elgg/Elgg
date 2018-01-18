<?php
/**
 * View a single page
 */

$guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'object', 'page');

/* @var $page ElggPage */
$page = get_entity($guid);

$container = $page->getContainerEntity();
if (!$container) {
	throw new \Elgg\EntityNotFoundException();
}

elgg_set_page_owner_guid($container->guid);

elgg_group_gatekeeper();

elgg_push_breadcrumb(elgg_echo('pages'), 'pages/all');

if ($container instanceof ElggUser) {
	elgg_push_breadcrumb($container->getDisplayName(), "pages/owner/{$container->username}");
} else if ($container instanceof ElggGroup) {
	elgg_push_breadcrumb($container->getDisplayName(), "pages/group/{$container->guid}");
}

pages_prepare_parent_breadcrumbs($page);

$title = $page->getDisplayName();
elgg_push_breadcrumb($title);

$content = elgg_view_entity($page, [
	'show_responses' => true,
]);

// can add subpage if can edit this page and write to container (such as a group)
if ($page->canEdit() && $container->canWriteToContainer(0, 'object', 'page')) {
	elgg_register_menu_item('title', [
		'name' => 'subpage',
		'href' => "pages/add/{$page->guid}",
		'text' => elgg_echo('pages:newchild'),
		'link_class' => 'elgg-button elgg-button-action',
	]);
}

$body = elgg_view_layout('content', [
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('pages/sidebar/navigation', [
		'page' => $page,
	]),
	'entity' => $page,
]);

echo elgg_view_page($title, $body);
