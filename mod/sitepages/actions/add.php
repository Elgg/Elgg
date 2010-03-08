<?php
/**
 * Elgg external pages: add/edit
 */

// Make sure we're logged as admin
admin_gatekeeper();

// Get input data
$contents = get_input('sitepagescontent', '', false);
$type = get_input('content_type');
$tags = get_input('sitepagestags');
$previous_guid = get_input('expage_guid');

// Cache to the session
$_SESSION['sitepages_content'] = $contents;
$_SESSION['sitepagestype'] = $type;
$_SESSION['sitepagestags'] = $tags;
		
// Convert string of tags into a preformatted array
$tagarray = string_to_tag_array($tags);
		
// Make sure the content exists
if (empty($contents)) {
	register_error(elgg_echo("sitepages:blank"));
	forward("mod/sitepages/add.php");
			
// Otherwise, save the new external page
} else {
	//remove the old external page
	if(get_entity($previous_guid)){
		delete_entity($previous_guid);
	}	
		
	// Initialise a new ElggObject
	$sitepages = new ElggObject();
	// Tell the system what type of external page it is
	$sitepages->subtype = $type;
	// Set its owner to the current user
	$sitepages->owner_guid = $_SESSION['user']->getGUID();
	// For now, set its access to public
	$sitepages->access_id = 2;
	// Set its title and description appropriately
	$sitepages->title = $type;
	$sitepages->description = $contents;
	// Before we can set metadata, save
	if (!$sitepages->save()) {
		register_error(elgg_echo("sitepages:error"));
		forward("mod/sitepages/add.php");
	}
	// Now let's add tags. We can pass an array directly to the object property! Easy.
	if (is_array($tagarray)) {
		$sitepages->tags = $tagarray;
	}
	// Success message
	system_message(elgg_echo("sitepages:posted"));
	// add to river
	add_to_river('river/sitepages/create','create',$_SESSION['user']->guid,$sitepages->guid);
	// Remove the cache
	unset($_SESSION['sitepages_content']); unset($_SESSION['sitepagestitle']); unset($_SESSION['sitepagestags']);
			
	// Forward back to the page
	forward("pg/sitepages/index.php?type={$type}");
}
