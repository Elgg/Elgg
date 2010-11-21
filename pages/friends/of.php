<?php
/**
 * Elgg friends of page
 *
 * @package Elgg
 * @subpackage Core
 */

$owner = elgg_get_page_owner();
if (!$owner) {
	gatekeeper();
	set_page_owner(get_loggedin_userid());
	$owner = elgg_get_page_owner();
}

$title = elgg_echo("friends:of:owned", array($owner->name));

$content = elgg_view_title($title);

$options = array(
	'relationship' => 'friend',
	'relationship_guid' => $owner->getGUID(),
	'inverse_relationship' => TRUE,
	'type' => 'user',
	'full_view' => FALSE
);
$content .= elgg_list_entities_from_relationship($options);

$body = elgg_view_layout('one_column_with_sidebar', array('content' => $content));

echo elgg_view_page($title, $body);
