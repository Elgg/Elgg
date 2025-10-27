<?php

$guid = (int) elgg_extract('guid', $vars);

/** @var \ElggPage $entity */
$entity = elgg_entity_gatekeeper($guid, 'object', 'page');

$container = $entity->getContainerEntity();
if (!$container) {
	throw new \Elgg\Exceptions\Http\EntityNotFoundException();
}

elgg_push_collection_breadcrumbs('object', 'page', $container);
pages_prepare_parent_breadcrumbs($entity);

// can add subpage if can edit this page and write to container (such as a group)
if ($entity->canEdit() && $container->canWriteToContainer(0, 'object', 'page')) {
	elgg_register_menu_item('title', [
		'name' => 'subpage',
		'icon' => 'plus',
		'href' => elgg_generate_url('add:object:page', [
			'guid' => $entity->guid,
		]),
		'text' => elgg_echo('pages:newchild'),
		'link_class' => 'elgg-button elgg-button-action',
	]);
}

echo elgg_view_page($entity->getDisplayName(), [
	'content' => elgg_view_entity($entity),
	'sidebar' => elgg_view('pages/sidebar/navigation', [
		'page' => $entity,
	]),
	'entity' => $entity,
	'filter_id' => 'pages/view',
]);
