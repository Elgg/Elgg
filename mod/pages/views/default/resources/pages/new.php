<?php
/**
 * Create a new page
 *
 * @package ElggPages
 */

elgg_gatekeeper();

$container_guid = (int) elgg_extract('guid', $vars);

elgg_entity_gatekeeper($container_guid);

$container = get_entity($container_guid);

$parent_guid = 0;
$page_owner = $container;
if (elgg_instanceof($container, 'object')) {
	$parent_guid = $container->getGUID();
	$page_owner = $container->getContainerEntity();
}

elgg_set_page_owner_guid($page_owner->getGUID());

elgg_group_gatekeeper();

// Make sure user has permissions to add to container
if (!$page_owner->canWriteToContainer(0, 'object', 'page') && !$page_owner->canWriteToContainer(0, 'object', 'page_top')) {
	register_error(elgg_echo('actionunauthorized'));
	forward(REFERER);
}

$title = elgg_echo('pages:add');
elgg_push_breadcrumb($title);

$vars = pages_prepare_form_vars(null, $parent_guid);
$content = elgg_view_form('pages/edit', array(), $vars);

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
