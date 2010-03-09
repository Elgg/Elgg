<?php

/**
 * Elgg blog archive page
 */

// Load Elgg engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
// Get the current page's owner
$page_owner = page_owner_entity();
if ($page_owner === false || is_null($page_owner)) {
	$page_owner = $_SESSION['user'];
	set_page_owner($_SESSION['guid']);
}
//set breadcrumbs if in a group
//$area1 = elgg_view('elggcampus_layout/breadcrumbs_general', array('object_type' => 'blog'));	
// Get timestamp upper and lower bounds
$timelower = (int) get_input('param2');
$timeupper = (int) get_input('param3');
if (empty($timelower)) {
	forward('pg/blog/'.$page_owner->username);
	exit;
}
if (empty($timeupper)) {
	$timeupper = $timelower + (86400 * 30);
}

// Set blog title
$area2 = elgg_view_title(sprintf(elgg_echo('date:month:'.date('m',$timelower)),date('Y',$timelower)));

set_context('search');
// Get a list of blog posts
$blogs = list_user_objects($page_owner->getGUID(),'blog',10,false,false,true,$timelower,$timeupper);
$area2 .= "<div id=\"blogs\">" . $blogs  . "<div class='clearfloat'></div></div>";
set_context('blog');

///if the logged in user is not looking at their stuff, display the ownerblock otherwise
//show the users favourites
if(page_owner()	!= get_loggedin_user()->guid){
	$area3 = elgg_view('blog/ownerblock');
}

//get the latest comments on user's blog posts
$comments = get_annotations(0, "object", "blog", "generic_comment", "", 0, 4, 0, "desc",0,0,page_owner());
$area3 .= elgg_view('elggcampus_layout/latest_comments', array('comments' => $comments));
//a view for the favourites plugin to extend
$area3 .= elgg_view("blogs/sidebar_options", array("object_type" => 'blog'));
//display archive
$area3 .= elgg_view("blog/archive");
		
// Display them in the page
$body = elgg_view_layout("one_column_with_sidebar", $area1 . $area2, $area3);
		
// Display page
page_draw(sprintf(elgg_echo('blog:user'),$page_owner->name),$body);