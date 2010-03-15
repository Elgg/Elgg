<?php

	/**
	 * Elgg blog friends page
	 * 
	 * @package ElggBlog
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	// Load Elgg engine
		define('everyoneblog','true');
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
	// Get the current page's owner
		$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($_SESSION['guid']);
		}
		if (!($page_owner instanceof ElggEntity)) forward();

	//set the title
        if($page_owner == $_SESSION['user']){
			$title = elgg_echo('blog:yourfriends');
		}else{
			$title = $page_owner->name . "'s " . elgg_echo('blog:friends');
		}
		
		$area2 = elgg_view_title($title);
		
		// Get a list of blog posts
		// note: this does not pass offset because list_user_friends_objects gets it from input
		$area2 .= list_user_friends_objects($page_owner->getGUID(),'blog',10,false);
		
	// Get categories, if they're installed
		global $CONFIG;
		$area3 = elgg_view('blog/categorylist',array('baseurl' => $CONFIG->wwwroot . 'search/?subtype=blog&owner_guid='.$page_owner->guid.'&friends='.$page_owner->guid.'&tagtype=universal_categories&tag=','subtype' => 'blog'));
		
	// Display them in the page
        $body = elgg_view_layout("two_column_left_sidebar", '', $area1 . $area2, $area3);
		
	// Display page
		page_draw($title, $body);
		
?>