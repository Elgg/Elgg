<?php
	/**
	 * Elgg file browser
	 *
	 * @package ElggFile
	 *
	 *
	 * TODO: File icons, download & mime types
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	// access check for closed groups
	group_gatekeeper();

	//set the title
	if (elgg_get_page_owner_guid() == get_loggedin_userid()) {
		$title = elgg_echo('file:yours');
		$area1 = elgg_view('page_elements/content_header', array('context' => "mine", 'type' => 'file'));
	} else {
		$title = elgg_echo("file:user",array(elgg_get_page_owner()->name));
		$area1 = elgg_view('page_elements/content_header', array('context' => "friends", 'type' => 'file'));
	}

	// Get objects
	elgg_push_context('search');
	$offset = (int)get_input('offset', 0);
	$area2 .= elgg_list_entities(array('types' => 'object', 'subtypes' => 'file', 'container_guid' => elgg_get_page_owner_guid(), 'limit' => 10, 'offset' => $offset, 'full_view' => FALSE));
	elgg_pop_context();

	$get_filter = get_filetype_cloud(elgg_get_page_owner_guid());
	if ($get_filter) {
		$area1 .= $get_filter;
	} else {
		$area2 .= "<p class='margin_top'>".elgg_echo("file:none")."</p>";
	}

	//get the latest comments on the current users files
	$comments = get_annotations(0, "object", "file", "generic_comment", "", 0, 4, 0, "desc",0,0,elgg_get_page_owner_guid());
	$area3 = elgg_view('annotation/latest_comments', array('comments' => $comments));

	$content = "<div class='files'>".$area1.$area2."</div>";

	$params = array(
		'content' => $content,
		'sidebar' => $area3
	);
	$body = elgg_view_layout('one_column_with_sidebar', $params);

	echo elgg_view_page($title, $body);
?>