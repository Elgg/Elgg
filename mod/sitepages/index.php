<?php
/**
 * Elgg Site pages
 */

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

admin_gatekeeper();
set_context('admin');
$type = get_input('type'); //the type of page e.g about, terms etc
if(!$type)
	$type = "front"; //default to the frontpage
	
// Set admin user for user block
set_page_owner($_SESSION['guid']);
	
//display the title
$title = elgg_view_title(elgg_echo('sitepages'));
	
// Display the correct form
if($type == "front")
	$edit = elgg_view('sitepages/forms/editfront');
elseif($type == "seo")
	$edit = elgg_view('sitepages/forms/editmeta');
else
	$edit = elgg_view('sitepages/forms/edit', array('type' => $type));
	
if($type == "front")
 	$area3 = elgg_view('sitepages/keywords'); //available keywords for the user
else
	$area3 = "";

// Display the menu
$body = elgg_view('page_elements/elgg_content',array('body' => elgg_view('sitepages/menu', array('type' => $type)).$edit));
		
// Display
page_draw(elgg_echo('sitepages'),elgg_view_layout("one_column_with_sidebar", '', $title . $body, $area3));