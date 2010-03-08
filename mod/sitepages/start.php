<?php
/**
 * Elgg external pages editor
 */
 
require_once(dirname(__FILE__) . '/sitepages_functions.php');

function sitepages_init() {
	global $CONFIG;
	// Register a page handler, so we can have nice URLs
	register_page_handler('sitepages','sitepages_page_handler');
		
	// Register a URL handler for external pages
	register_entity_url_handler('sitepages_url','object','sitepages');
		
	// extend views
	elgg_extend_view('footer/links', 'sitepages/footer_menu');
	elgg_extend_view('metatags', 'sitepages/metatags');
	
	// Replace the default index page if user has requested
	if (get_plugin_setting('ownfrontpage', 'externalpages') == 'yes'){
		register_plugin_hook('index','system','custom_index');
	}
		
}

function custom_index() {
	if (!@include_once(dirname(__FILE__) . "/frontpage.php")) return false;
	return true;
}
	
/**
 * Page setup. Adds admin controls to the admin panel.
 *
 */
function sitepages_pagesetup(){
	if (get_context() == 'admin' && isadminloggedin()) {
		global $CONFIG;
		add_submenu_item(elgg_echo('sitepages'), $CONFIG->wwwroot . 'pg/sitepages/');
	}
}
	
function sitepages_url($expage) {
	global $CONFIG;
	return $CONFIG->url . "pg/sitepages/";
}
	
function sitepages_page_handler($page) {
	global $CONFIG;
	if ($page[0]){
		switch ($page[0]){
				case "read":		set_input('sitepages',$page[1]);
										include(dirname(__FILE__) . "/read.php");
										break;
				default : include($CONFIG->pluginspath . "sitepages/index.php"); 
		}
	}else{
		include($CONFIG->pluginspath . "sitepages/index.php"); 
	}
}
	
// Initialise log browser
register_elgg_event_handler('init','system','sitepages_init');
register_elgg_event_handler('pagesetup','system','sitepages_pagesetup');
	
// Register actions
global $CONFIG;
register_action("sitepages/add",false,$CONFIG->pluginspath . "sitepages/actions/add.php");
register_action("sitepages/addfront",false,$CONFIG->pluginspath . "sitepages/actions/addfront.php");
register_action("sitepages/addmeta",false,$CONFIG->pluginspath . "sitepages/actions/addmeta.php");
register_action("sitepages/edit",false,$CONFIG->pluginspath . "sitepages/actions/edit.php");
register_action("sitepages/delete",false,$CONFIG->pluginspath . "sitepages/actions/delete.php");