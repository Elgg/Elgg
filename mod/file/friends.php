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

	$owner = elgg_get_page_owner();

	$title = elgg_echo("file:friends",array($owner->name));
	$area1 = elgg_view('page_elements/content_header', array('context' => "friends", 'type' => 'file'));
	elgg_push_context('search');
	// offset is grabbed in list_user_friends_objects
	$content = list_user_friends_objects($owner->guid, 'file', 10, false);
	elgg_pop_context();

	$area1 .= get_filetype_cloud($owner->guid, true);

	// handle case where friends don't have any files
	if (empty($content)) {
		$area2 .= "<p class='margin-top'>".elgg_echo("file:none")."</p>";
	} else {
		$area2 .= $content;
	}

	//get the latest comments on all files
	$comments = get_annotations(0, "object", "file", "generic_comment", "", 0, 4, 0, "desc");
	$area3 = elgg_view('annotation/latest_comments', array('comments' => $comments));

	$content = "<div class='files'>".$area1.$area2."</div>";
	$params = array(
		'content' => $content,
		'sidebar' => $area3
	);
	$body = elgg_view_layout('one_column_with_sidebar', $params);

	echo elgg_view_page($title, $body);
?>