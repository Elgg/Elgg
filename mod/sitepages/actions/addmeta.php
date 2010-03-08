<?php
/**
 * Elgg SEO: add/edit
 */

// Make sure we're logged as admin
admin_gatekeeper();

// Get input data
$description = get_input('description', '', false);
$metatags = get_input('metatags', '', false);
$previous_guid = get_input('seo_guid');
		
//remove the old front page
if(get_entity($previous_guid)){
	delete_entity($previous_guid);
}

// Cache to the session
$_SESSION['description'] = $description;
$_SESSION['metatags'] = $metatags;
			
// Initialise a new ElggObject
$seo = new ElggObject();
// Tell the system what type of external page it is
$seo->subtype = "sitemeta";
// Set its owner to the current user
$seo->owner_guid = $_SESSION['user']->getGUID();
// Set its access to public
$seo->access_id = 2;
// Set its title and description appropriately
$seo->title = $metatags;
$seo->description = $description;
			
// Before we can set metadata, save
if (!$seo->save()) {
	register_error(elgg_echo("sitepages:error"));
	forward("pg/sitepages/index.php?type=seo");
}

// Success message
system_message(elgg_echo("sitepages:seocreated"));

// Remove the cache
unset($_SESSION['description']); unset($_SESSION['metatags']);
	
	
// Forward back to the page
forward("pg/sitepages/index.php?type=seo");
