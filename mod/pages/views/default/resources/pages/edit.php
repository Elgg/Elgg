<?php
/**
 * Edit a page
 *
 * @package ElggPages
 */

elgg_gatekeeper();

$page_guid = (int)get_input('guid');
$revision = (int)get_input('annotation_id');
$page = get_entity($page_guid);
if (!pages_is_page($page)) {
	register_error(elgg_echo('noaccess'));
	forward('');
}

$container = $page->getContainerEntity();
if (!$container) {
	register_error(elgg_echo('noaccess'));
	forward('');
}

elgg_set_page_owner_guid($container->getGUID());

elgg_push_breadcrumb($page->title, $page->getURL());
elgg_push_breadcrumb(elgg_echo('edit'));

$title = elgg_echo("pages:edit");

if ($page->canEdit()) {

	if ($revision) {
		$revision = elgg_get_annotation_from_id($revision);
		if (!$revision || !($revision->entity_guid == $page_guid)) {
			register_error(elgg_echo('pages:revision:not_found'));
			forward(REFERER);
		}
	}

	$vars = pages_prepare_form_vars($page, $page->parent_guid, $revision);
	
	$content = elgg_view_form('pages/edit', array(), $vars);
} else {
	$content = elgg_echo("pages:noaccess");
}

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
