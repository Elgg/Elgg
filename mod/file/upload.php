<?php
	/**
	 * Elgg file browser uploader
	 * 
	 * @package ElggFile
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	gatekeeper();
	if (is_callable('group_gatekeeper')) {
		group_gatekeeper();
	}

	// Render the file upload page

	$container_guid = page_owner();
	$area2 = elgg_view_title($title = elgg_echo('file:upload'));
	$area2 .= elgg_view("file/upload", array('container_guid' => $container_guid));
	$body = elgg_view_layout('two_column_left_sidebar', '', $area2);
	
	page_draw(elgg_echo("file:upload"), $body);
	
?>