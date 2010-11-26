<?php
	/**
	 * Elgg file browser
	 * 
	 * @package ElggFile
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	
	if (is_callable('group_gatekeeper')) {
		group_gatekeeper();
	}
	
	$owner = page_owner_entity();
	
	$title = sprintf(elgg_echo("file:friends"),$owner->name);
	
	$area2 = elgg_view_title($title);
	
	set_context('search');
	// offset is grabbed in list_user_friends_objects
	$content = list_user_friends_objects($owner->guid, 'file', 10, false);
	set_context('file');
	$area1 = get_filetype_cloud($owner->guid, true);
	
	// handle case where friends don't have any files
	if (empty($content)) {
		$area2 .= elgg_view('page_elements/contentwrapper',array('body' => elgg_echo("file:none")));
	} else {
		$area2 .= $content;
	}

	$body = elgg_view_layout('two_column_left_sidebar',$area1, $area2);
	
	page_draw($title, $body);
?>