<?php
/**
 * Create a new page
 */

use Elgg\Exceptions\Http\EntityPermissionsException;

$container = false;

$parent_guid = (int) elgg_extract('guid', $vars);
if (!$parent_guid) {
	$container = elgg_get_page_owner_entity();
	if (!$container) {
		$container = elgg_get_logged_in_user_entity();
	}
} else {
	$parent = get_entity($parent_guid);
	if ($parent instanceof ElggPage) {
		$container = $parent->getContainerEntity();
	} else if ($parent instanceof ElggEntity) {
		$container = $parent;
		$parent = null;
		$parent_guid = 0;
	}
}

if ($parent && !$parent->canEdit()) {
	throw new EntityPermissionsException();
}

if (!$container || !$container->canWriteToContainer(0, 'object', 'page')) {
	throw new EntityPermissionsException();
}

elgg_set_page_owner_guid($container->guid);

elgg_push_collection_breadcrumbs('object', 'page', $container);

if ($parent instanceof ElggPage) {
	pages_prepare_parent_breadcrumbs($parent);
	elgg_push_breadcrumb($parent->getDisplayName(), $parent->getURL());
}

$vars = pages_prepare_form_vars(null, $parent_guid);

echo elgg_view_page(elgg_echo('add:object:page'), [
	'content' => elgg_view_form('pages/edit', [], $vars),
	'filter_id' => 'pages/edit',
]);
