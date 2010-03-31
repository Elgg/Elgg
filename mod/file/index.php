<?php
	/**
	 * Elgg file browser
	 * 
	 * @package ElggFile
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 * 
	 * 
	 * TODO: File icons, download & mime types
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	// access check for closed groups
	group_gatekeeper();
	
	//set the title
	if (page_owner() == get_loggedin_userid()) {
		$title = elgg_echo('file:yours');
	} else {
		$title = sprintf(elgg_echo("file:user"),page_owner_entity()->name);
	}
			
	$area2 = elgg_view_title($title);
		
	// Get objects
	set_context('search');
	$offset = (int)get_input('offset', 0);
	$area2 .= elgg_list_entities(array('types' => 'object', 'subtypes' => 'file', 'container_guid' => page_owner(), 'limit' => 10, 'offset' => $offset, 'full_view' => FALSE));
	set_context('file');
	$get_filter = get_filetype_cloud(page_owner());
	if ($get_filter) {
		$area1 = $get_filter;
	} else {
		$area2 .= elgg_view('page_elements/contentwrapper',array('body' => elgg_echo("file:none")));
	}

	$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2);
	
	page_draw($title, $body);
?>