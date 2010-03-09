<?php

	/**
	 * Elgg read blog post page
	 * @package ElggBlog
	 * @copyright Curverider Ltd 2008-2009
	 */

// Load Elgg engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

// Get the specified blog post
$post = (int) get_input('blogpost');

// If we can get out the blog post ...
if ($blogpost = get_entity($post)) {
	// Set variables
	$blog_acl = '';			
	// Set the page owner
	if ($blogpost->container_guid)
		set_page_owner($blogpost->container_guid);
	else
		set_page_owner($blogpost->owner_guid);
		
	//set breadcrumbs
	//$area2 = elgg_view('elggcampus_layout/breadcrumbs', array('object_title' => $blogpost->title, 'object_type' => 'blog'));
	// Display it
	$area2 .= elgg_view_entity($blogpost, true);
	//get the blog's access level
	$blog_acl = get_readable_access_level($blogpost->access_id);					
	// Set the title appropriately
	$title = $blogpost->title;
	//set blog ownerblock if not your own
	if($blogpost->owner_guid != get_loggedin_user()->guid){
		$area3 = elgg_view('blog/ownerblock');
	}
	//display the read sidebar
	//$area3 .= elgg_view('blog/read_sidebar', array('blog_acl' => $blog_acl, 'entity' => $blogpost));
	//if the logged in user is not looking at their stuff, display the ownerblock otherwise
	//show the users favourites
	if(page_owner()	!= get_loggedin_user()->guid){
		$area3 = elgg_view('blog/ownerblock');
	}else{	
		//a view for the favourites plugin to extend
		$area3 .= elgg_view("blogs/sidebar_options", array("object_type" => 'blog'));
	}
	//display archive
	$area3 .= elgg_view("blog/archive");
	//get the latest comments on user's blog posts
	$comments = get_annotations(0, "object", "blog", "generic_comment", "", 0, 4, 0, "desc",0,0,page_owner());
	$area3 .= elgg_view('page_elements/latest_comments', array('comments' => $comments));

	// Display through the correct canvas area
	$body = elgg_view_layout("one_column_with_sidebar", $area1.$area2, $area3);
			
// If we're not allowed to see the blog post
} else {		
	// Display the 'post not found' page instead
	$body = elgg_view("blog/notfound");
	$title = elgg_echo("blog:notfound");		
}
		
// Display page
page_draw($title,$body);
		
?>