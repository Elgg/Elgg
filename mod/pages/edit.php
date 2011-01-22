<?php
/**
 * Edit a page
 *
 * @package ElggPages
 */

gatekeeper();

$page_guid = get_input('guid');
$page = get_entity($page_guid);
if (!$page) {

}

$container = $page->getContainerEntity();
if (!$container) {

}

elgg_set_page_owner_guid($container->getGUID());

elgg_push_breadcrumb($page->title, $page->getURL());
elgg_push_breadcrumb(elgg_echo('edit'));

$title = elgg_echo("pages:edit");

if ($page->canEdit()) {
	$vars = pages_prepare_form_vars($page);
	$content = elgg_view_form('pages/edit', array(), $vars);
} else {
	$content = elgg_echo("pages:noaccess");
}

$body = elgg_view_layout('content', array(
	'filter' => '',
	'buttons' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
