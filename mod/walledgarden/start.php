<?php

/**
 * Walled garden support.
 */

function walledgarden_init(){
	global $CONFIG;
		
	$CONFIG->disable_registration = true;
		
	// elgg_set_viewtype('default');
	elgg_extend_view('pageshells/pageshell', 'walledgarden/walledgarden');
	elgg_extend_view('css','walledgarden/css');
	
	// restrict external user creation
	register_plugin_hook('new_twitter_user', 'twitter_service', 'walledgarden_new_external_user');
	register_plugin_hook('new_facebook_user', 'facebook_service', 'walledgarden_new_external_user');
	
	if(!isloggedin())
		register_plugin_hook('index','system','walledgarden_index');
}
	
function walledgarden_pagesetup() {
		
	global $CONFIG;
	if (current_page_url() != $CONFIG->url
		&& !defined('externalpage')
		&& !isloggedin()) {
			forward();
			exit;
		}
		
}
	
function walledgarden_index() {
			
	if (!include_once(dirname(dirname(__FILE__))) . "/walledgarden/index.php") {
		return false;
	}
	return true;
			
}

function walledgarden_new_external_user($hook, $entity_type, $returnvalue, $params) {
	// do not allow new users to be created within the walled-garden
	register_error(elgg_echo('walledgarden:new_user:fail'));
	return FALSE;
}
	
/**
 * This is so the homepage can have its own pageshell
 **/
 
function page_draw_walledgarden($title, $body, $sidebar = "") {

	// Draw the page
	$output = elgg_view('page_shells/walled_garden_index', array(
		'title' => $title,
		'body' => $body,
		'sidebar' => $sidebar,
		'sysmessages' => system_messages(null,"")
		)
	);
	$split_output = str_split($output, 1024);

	foreach($split_output as $chunk) {
		echo $chunk;
	}
}
	
register_elgg_event_handler('init','system','walledgarden_init');
register_elgg_event_handler('pagesetup','system','walledgarden_pagesetup');
