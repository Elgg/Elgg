<?php
	/**
	 * Elgg file browser
	 * 
	 * @package ElggFile
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	
	$limit = get_input("limit", 10);
	$offset = get_input("offset", 0);
	
	// Get the current page's owner
		$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($_SESSION['guid']);
		}
	
	$title = elgg_echo('file:all');
	
	// Get objects
	$area2 = elgg_view_title($title);
	$area1 = get_filetype_cloud(); // the filter
	set_context('search');
	$area2 .= elgg_list_entities(array('types' => 'object', 'subtypes' => 'file', 'limit' => $limit, 'offset' => $offset, 'full_view' => FALSE));
	set_context('file');
		
	$body = elgg_view_layout('two_column_left_sidebar',$area1, $area2);

	page_draw($title, $body);
