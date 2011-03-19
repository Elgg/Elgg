<?php
/**
 * Elgg messages inbox page
 *
 * @package ElggMessages
*/

gatekeeper();

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner) {
	register_error(elgg_echo());
	forward();
}

elgg_push_breadcrumb(elgg_echo('messages:inbox'));

$title = elgg_echo('messages:user', array($page_owner->name));

$list = elgg_list_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'messages',
	'metadata_name' => 'toId',
	'metadata_value' => elgg_get_page_owner_guid(),
	'owner_guid' => elgg_get_page_owner_guid(),
	'full_view' => false,
));

$body_vars = array(
	'folder' => 'inbox',
	'list' => $list,
);
$content = elgg_view_form('messages/process', array(), $body_vars);

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => elgg_echo('messages:inbox'),
	'filter' => '',
));

echo elgg_view_page($title, $body);
