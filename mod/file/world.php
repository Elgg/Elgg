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
		$page_owner = elgg_get_page_owner();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = get_loggedin_user();
			set_page_owner(get_loggedin_userid());
		}
	
	$title = elgg_echo('file:all');
	
	// Get objects
	$area1 = elgg_view('page_elements/content_header', array('context' => "everyone", 'type' => 'file'));
	$area1 .= get_filetype_cloud(); // the filter
	elgg_push_context('search');
	$area2 .= elgg_list_entities(array('types' => 'object', 'subtypes' => 'file', 'limit' => $limit, 'offset' => $offset, 'full_view' => FALSE));
	elgg_pop_context();

	//get the latest comments on all files
	$comments = get_annotations(0, "object", "file", "generic_comment", "", 0, 4, 0, "desc");
	$area3 = elgg_view('comments/latest', array('comments' => $comments));
	
	$content = "<div class='files'>".$area1.$area2."</div>";
	$params = array(
		'content' => $content,
		'sidebar' => $area3
	);
	$body = elgg_view_layout('one_column_with_sidebar', $params);

	echo elgg_view_page($title, $body);
