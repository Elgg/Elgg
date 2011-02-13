<?php

	/**
	 * Elgg custom index
	 * 
	 * @package ElggCustomIndex
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	// Get the Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
    //get required data		
	set_context('search');//display results in search mode, which is list view
	//grab the latest 4 blog posts. to display more, change 4 to something else
	$blogs = elgg_list_entities(array('type' => 'object', 'subtype' => 'blog', 'limit' => 4, 'full_view' => FALSE, 'view_type_toggle' => FALSE, 'pagination' => FALSE));
	//grab the latest bookmarks
	$bookmarks = elgg_list_entities(array('type' => 'object', 'subtype' => 'bookmarks', 'limit' => 4, 'full_view' => FALSE, 'view_type_toggle' => FALSE, 'pagination' => FALSE));
	//grab the latest files
	$files = elgg_list_entities(array('type' => 'object', 'subtype' => 'file', 'limit' => 4, 'full_view' => FALSE, 'view_type_toggle' => FALSE, 'pagination' => FALSE));
	//get the newest members who have an avatar
	$newest_members = elgg_get_entities_from_metadata(array('metadata_names' => 'icontime', 'types' => 'user', 'limit' => 10));
	//newest groups
	$groups = elgg_list_entities(array(type => 'group', 'limit' => 4, 'full_view' => FALSE, 'view_type_toggle' => FALSE, 'pagination' => FALSE));
	//grab the login form
	$login = elgg_view("account/forms/login");
	
    //display the contents in our new canvas layout
	$body = elgg_view_layout('new_index',$login, $files, $newest_members, $blogs, $groups, $bookmarks);
   
    page_draw($title, $body);
		
?>