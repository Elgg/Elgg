<?php
/**
 *	Page Content header 
	holds the filter menu and any content action buttons
	used on  bookmarks, blog, file, pages,
 **/
	 
global $CONFIG;
	
// set variables
$page_owner = page_owner_entity();
$filter_context = $vars['context']; // so we know if the user is looking at their own, everyone's or all friends
$type = $vars['type']; // get the object type 
$mine_selected = '';
$all_selected = '';
$friend_selected = '';
$action_buttons = '';
$title = '';
/* $dash_selected = ''; */
	 				
if(!($page_owner instanceof ElggGroup)){
	if($filter_context == 'mine') {
		$mine_selected = "SELECTED";
	}
	if($filter_context == 'everyone') {
		$all_selected = "SELECTED";	
	}
	if($filter_context == 'friends') {
		$friend_selected = "SELECTED";
	}
	if($filter_context == 'action') { 
		// if this is an action page, we'll not be displaying the filter
	}
/*
	if($filter_context == 'dashboard') 
		$dash_selected = "SELECTED";
*/
}	

// must be logged in to see the filter menu and any action buttons
if(isloggedin()) {
	// if we're not on an action page (add bookmark, create page, upload a file etc)
	if ($filter_context != 'action') {
		$location_filter = "<select onchange=\"window.open(this.options[this.selectedIndex].value,'_top')\" name=\"file_filter\" class='styled' >";
		$location_filter .= "<option {$mine_selected} class='select_option' value=\"{$vars['url']}pg/{$type}/{$_SESSION['user']->username}\" >" . elgg_echo($type . ':yours') . "</option>";
		$location_filter .= "<option {$all_selected} class='select_option' value=\"{$vars['url']}mod/{$type}/all.php\">" . elgg_echo($type . ':all') . "</option>";
		$location_filter .= "<option {$friend_selected} class='select_option' value=\"{$vars['url']}pg/{$type}/{$_SESSION['user']->username}/friends/\">". elgg_echo($type . ':friends') . "</option>";
		$location_filter .= "</select>";
		$location_filter = "<div class='content_header_filter'>".$location_filter."</div>";
		
		// action buttons
		if(get_context() != 'bookmarks'){
			$url = $CONFIG->wwwroot . "pg/{$type}/". $page_owner->username . "/new/";
		} else {
			$url = $CONFIG->wwwroot . "pg/{$type}/". $page_owner->username . "/add";
		}
		$action_buttons = "<a href=\"{$url}\" class='action_button'>" . elgg_echo($type . ':new') . "</a>";
		$action_buttons = "<div class='content_header_options'>".$action_buttons."</div>";

	} else {
		// if we're on an action page - we'll just have a simple page title, and no filter menu
		$title = "<div class='content_header_title'>".elgg_view_title($title = elgg_echo($type . ':add'))."</div>";
	}
}	
?>
<!-- construct the content area header -->
<div id="content_header" class="clearfloat">
	<?php echo $title; ?>
	<?php echo $location_filter; ?>
	<?php echo $action_buttons; ?>
</div>
