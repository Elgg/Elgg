<?php
/**
 * View a single page
 */

use Elgg\Exceptions\Http\EntityNotFoundException;

$guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'object', 'page');

/* @var $page ElggPage */
$page = get_entity($guid);

$container = $page->getContainerEntity();
if (!$container) {
	throw new EntityNotFoundException();
}

elgg_push_collection_breadcrumbs('object', 'page', $container);
pages_prepare_parent_breadcrumbs($page);

// can add subpage if can edit this page and write to container (such as a group)
if ($page->canEdit() && $container->canWriteToContainer(0, 'object', 'page')) {
	elgg_register_menu_item('title', [
		'name' => 'subpage',
		'icon' => 'plus',
		'href' => elgg_generate_url('add:object:page', [
			'guid' => $page->guid,
		]),
		'text' => elgg_echo('pages:newchild'),
		'link_class' => 'elgg-button elgg-button-action',
	]);
}

echo elgg_view_page($page->getDisplayName(), [
	'content' => elgg_view_entity($page, [
		'show_responses' => true,
	]),
	'sidebar' => elgg_view('pages/sidebar/navigation', [
		'page' => $page,
	]),
	'entity' => $page,
	'filter_id' => 'pages/view',
]);
