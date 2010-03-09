<?php

/**
* Elgg blog edit entry page
*/

//Load Elgg engine
	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	gatekeeper();
		
//Get the current page's owner
	$page_owner = page_owner_entity();
	if ($page_owner === false || is_null($page_owner)) {
		$page_owner = $_SESSION['user'];
		set_page_owner($_SESSION['guid']);
	}
		
//Get the post, if it exists
	$blogpost = (int) get_input('blogpost');
	if ($post = get_entity($blogpost)) {	
		if ($post->canEdit()) {	
			//$area1 = elgg_view('elggcampus_layout/breadcrumbs_edit', array('object' => $post, 'object_type' => 'blog'));
			$area1 .= elgg_view("blog/forms/edit", array('entity' => $post));
			$body = elgg_view_layout("one_column", $area1);
		}	
	}
		
//Display page
	page_draw(sprintf(elgg_echo('blog:editpost'),$post->title),$body);