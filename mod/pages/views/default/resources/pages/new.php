<?php

/**
 * Create a new page
 *
 * @package ElggPages
 */
elgg_gatekeeper();

$container = false;

$parent_guid = (int) elgg_extract('guid', $vars);
if (!$parent_guid) {
	$container = elgg_get_page_owner_entity();
	if (!$container) {
		$container = elgg_get_logged_in_user_entity();
	}
	$subtype = 'page_top';
} else {
	$parent = get_entity($parent_guid);
	if (pages_is_page($parent)) {
		$container = $parent->getContainerEntity();
		$subtype = 'page';
	} else if (elgg_instanceof($parent)) {
		$container = $parent;
		$parent = null;
		$parent_guid = 0;
		$subtype = 'page_top';
	}
}

if ($parent && !$parent->canEdit()) {
	register_error(elgg_echo('noaccess'));
	forward('', '403');
}

if (!$container || !$container->canWriteToContainer(0, 'object', $subtype)) {
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

if ($subtype == 'page_top') {
	$title = elgg_echo('pages:add');
} else {
	$title = elgg_echo('pages:newchild');
}
elgg_push_breadcrumb($title);

$vars = pages_prepare_form_vars(null, $parent_guid);
$content = elgg_view_form('pages/edit', [], $vars);

$body = elgg_view_layout('content', [
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('pages/sidebar/navigation', [
		'page' => $parent,
	]),
		]);

echo elgg_view_page($title, $body);
