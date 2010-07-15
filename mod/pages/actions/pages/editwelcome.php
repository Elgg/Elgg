<?php
	/**
	 * Elgg Pages Edit welcome message
	 * 
	 * @package ElggPages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	// Load configuration
	global $CONFIG;
	
	gatekeeper();

	// Get group fields
	$message = get_input("pages_welcome");
	$owner_guid = get_input("owner_guid");
	$object_guid = get_input("object_guid");
	$access_id = (int) get_input("access_id");
	
	//check to see if this is an edit or new welcome message
	if($object_guid){
t	
t	//it is an edit so grab the object
t	$welcome = get_entity($object_guid);
		if ($welcome->getSubtype() == "pages_welcome" && $welcome->canEdit()) {
t		
t		$welcome->description = $message;
t		$welcome->access_id = $access_id; 
t		$welcome->save();
t		system_message(elgg_echo("pages:welcomeposted"));
t		
		} else {
t		
t		register_error(elgg_echo("pages:welcomeerror"));
t		
		}
t	
t	
	}else{
	
tt//it is a new welcome object
t	if ($owner_guid){
tt	
t		$welcome = new ElggObject();
t		// Tell the system it's a pages welcome message
t		$welcome->subtype = "pages_welcome";
t		$welcome->title = "Welcome";
t		$welcome->description = $message;
t		$welcome->access_id = $access_id;
t		
t		// Set the owner
t		$welcome->owner_guid = $owner_guid;
t		
t	t// save
t		if (!$welcome->save()){
t			register_error(elgg_echo("pages:welcomeerror"));
t		} else {
tt		system_message(elgg_echo("pages:welcomeposted"));
t		}
t
t		
t	} else {
tt	
tt	register_error(elgg_echo("pages:welcomeerror"));
tt	
t	}
t	
	}//end of first if statement
t	
	// Forward to the main blog page
	forward("pg/pages/owned/" . get_user($owner_guid)->username);
	exit;
	
?>
