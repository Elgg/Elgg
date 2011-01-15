<?php
/**
 * Create a new page
 *
 * @package ElggPages
 */

gatekeeper();

$container_guid = get_input('guid');

$container = get_entity($container_guid);
if (!$container) {

}

$page_owner = $container;
if (elgg_instanceof($container, 'object')) {
	$page_owner = $container->getContainerEntity();
}

elgg_set_page_owner_guid($page_owner->getGUID);

$title = elgg_echo('pages:add');
elgg_push_breadcrumb($title);

$content = elgg_view("forms/pages/edit");

$body = elgg_view_layout('content', array(
	'filter' => '',
	'buttons' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
