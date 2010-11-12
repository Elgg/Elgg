<?php
/**
 * Edit a page form
 *
 * @package ElggPages
 */
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
gatekeeper();

$page_guid = get_input('page_guid');
$pages = get_entity($page_guid);

// Get the current page's owner
if ($container = $pages->container_guid) {
	set_page_owner($container);
}

$page_owner = elgg_get_page_owner();

if ($page_owner === false || is_null($page_owner)) {
	$page_owner = get_loggedin_user();
	set_page_owner($page_owner->getGUID());
}

$title = elgg_echo("pages:edit");
$body = elgg_view_title($title);

if ($pages && ($pages->canEdit())) {
	$body .= elgg_view("forms/pages/edit", array('entity' => $pages));
} else {
	$body .= elgg_echo("pages:noaccess");
}

$body = elgg_view_layout('one_column_with_sidebar', array('content' => $body));

echo elgg_view_page($title, $body);