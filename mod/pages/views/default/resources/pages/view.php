<?php
/**
 * View a single page
 *
 * @package ElggPages
 */

$guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'object');

$page = get_entity($guid);
if (!pages_is_page($page)) {
	forward('', '404');
}

$container = $page->getContainerEntity();
if (!$container) {
	forward('', '404');
}

elgg_set_page_owner_guid($container->guid);

elgg_group_gatekeeper();

if ($container instanceof ElggUser) {
	elgg_push_breadcrumb($container->getDisplayName(), "pages/owner/$container->username");
} else if ($container instanceof ElggGroup) {
	elgg_push_breadcrumb($container->getDisplayName(), "pages/group/$container->guid");
}

pages_prepare_parent_breadcrumbs($page);

$title = $page->getDisplayName();
elgg_push_breadcrumb($title);

$content = elgg_view_entity($page, array('full_view' => true));
$content .= elgg_view_comments($page);

// can add subpage if can edit this page and write to container (such as a group)
if ($page->canEdit() && $container->canWriteToContainer(0, 'object', 'page')) {
	elgg_register_menu_item('title', array(
			'name' => 'subpage',
			'href' => "pages/add/$page->guid",
			'text' => elgg_echo('pages:newchild'),
			'link_class' => 'elgg-button elgg-button-action',
	));
}

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('pages/sidebar/navigation', [
		'page' => $page,
	]),
	'entity' => $page,
));

echo elgg_view_page($title, $body);
