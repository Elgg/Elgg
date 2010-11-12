<?php
/**
 * Elgg file saver
 *
 * @package ElggFile
 */

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

gatekeeper();

// Render the file upload page

$file_guid = (int) get_input('file_guid');
$file = get_entity($file_guid);
if (!$file) {
	forward();
}

// Set the page owner
$page_owner = elgg_get_page_owner();
if (!$page_owner) {
	$container_guid = $file->container_guid;
	if ($container_guid) {
		set_page_owner($container_guid);
	}
}

if (!$file->canEdit()) {
	forward();
}

$title = elgg_echo('file:edit');
$area1 = elgg_view_title($title);
$area1 .= elgg_view("file/upload", array('entity' => $file));

$body = elgg_view_layout('one_column_with_sidebar', array('content' => $area1));
echo elgg_view_page($title, $body);
