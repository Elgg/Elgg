<?php

/**
 * Edit a page
 *
 * @package ElggPages
 */
elgg_gatekeeper();

$page_guid = (int)elgg_extract('guid', $vars);
$page = get_entity($page_guid);

if (!pages_is_page($page) || !$page->canEdit()) {
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
	elgg_push_breadcrumb($container->getDisplayName(), "pages/owner/$container->username");
} else if ($container instanceof ElggGroup) {
	elgg_push_breadcrumb($container->getDisplayName(), "pages/group/$container->guid");
}

if ($parent) {
	pages_prepare_parent_breadcrumbs($parent);
	elgg_push_breadcrumb($parent->getDisplayName(), $parent->getURL());
}

elgg_push_breadcrumb(elgg_echo('edit'));

$title = elgg_echo("pages:edit");

$vars = pages_prepare_form_vars($page, $page->parent_guid);
$content = elgg_view_form('pages/edit', [], $vars);

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('pages/sidebar/navigation', [
		'page' => $page,
	]),
		));

echo elgg_view_page($title, $body);
