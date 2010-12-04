<?php
	/**
	 * Elgg file browser uploader
	 * 
	 * @package ElggFile
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	gatekeeper();
	if (is_callable('group_gatekeeper')) {
		group_gatekeeper();
	}

	// Render the file upload page

	$container_guid = elgg_get_page_owner_guid();
	$area1 = elgg_view_title($title = elgg_echo('file:upload'));
	$area1 .= elgg_view("file/upload", array('container_guid' => $container_guid));
	$body = elgg_view_layout('one_column_with_sidebar', array('content' => $area1));
	
	echo elgg_view_page(elgg_echo("file:upload"), $body);
	
?>