<?php
/**
 * Elgg front page: add/edit
 */

// Make sure we're logged as admin
admin_gatekeeper();

// Get input data
$frontContents = get_input('frontContents');
$css = get_input('css');
$previous_guid = get_input('front_guid');
	
//remove the old front page
if(get_entity($previous_guid)){
	delete_entity($previous_guid);
}

//var_export($pageshell);exit;

// Cache to the session
$_SESSION['pageshell'] = $pageshell;
$_SESSION['css'] = $css;
			
// Initialise a new ElggObject
$frontpage = new ElggObject();
// Tell the system what type of external page it is
$frontpage->subtype = "frontpage";
// Set its owner to the current user
$frontpage->owner_guid = $_SESSION['user']->getGUID();
// Set its access to public
$frontpage->access_id = 2;
// Set its title and description appropriately
$frontpage->title = $css;
$frontpage->description = $frontContents;
			
// Before we can set metadata, save
if (!$frontpage->save()) {
	register_error(elgg_echo("sitepages:error"));
	forward("pg/sitepages/index.php?type=front");
}

// Success message
system_message(elgg_echo("sitepages:posted"));

// Remove the cache
unset($_SESSION['css']); unset($_SESSION['pageshell']);
	
	
// Forward back to the page
forward("pg/sitepages/index.php?type=front");
