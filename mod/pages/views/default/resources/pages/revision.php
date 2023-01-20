<?php
/**
 * View a revision of page
 */

use Elgg\Exceptions\Http\EntityNotFoundException;
use Elgg\Exceptions\Http\EntityPermissionsException;

$id = (int) elgg_extract('id', $vars);
$annotation = elgg_get_annotation_from_id($id);
if (!$annotation instanceof \ElggAnnotation) {
	throw new EntityNotFoundException();
}

$page = $annotation->getEntity();
if (!$page instanceof \ElggPage || !$page->canEdit()) {
	throw new EntityPermissionsException();
}

elgg_entity_gatekeeper($page->container_guid);

elgg_set_page_owner_guid($page->container_guid);

$title = "{$page->getDisplayName()}: " . elgg_echo('pages:revision');

elgg_push_collection_breadcrumbs('object', 'page', $page->getContainerEntity());

pages_prepare_parent_breadcrumbs($page);
elgg_push_breadcrumb($page->getDisplayName(), $page->getURL());
elgg_push_breadcrumb(elgg_echo('pages:history'), elgg_generate_url('history:object:page', ['guid' => $page->guid]));

echo elgg_view_page($title, [
	'content' => elgg_view_entity($page, [
		'revision' => $annotation,
	]),
	'filter_id' => 'pages/history',
	'filter_value' => 'revision',
]);
