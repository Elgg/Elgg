<?php
	/**
	 * Elgg Pages Edit welcome message
	 * 
	 * @package ElggPages
	 */

	// Load configuration
	global $CONFIG;
	
	gatekeeper();

	// Get group fields
	$message = get_input("pages_welcome");
	$container_guid = get_input("owner_guid");
	$object_guid = get_input("object_guid");
	$access_id = (int) get_input("access_id");
	
	//check to see if this is an edit or new welcome message
	if($object_guid){
    	
    	//it is an edit so grab the object
    	$welcome = get_entity($object_guid);
		if ($welcome->getSubtype() == "pages_welcome" && $welcome->canEdit()) {
    		
    		$welcome->description = $message;
    		$welcome->access_id = $access_id; 
    		$welcome->save();
    		system_message(elgg_echo("pages:welcomeposted"));
    		
		} else {
    		
    		register_error(elgg_echo("pages:welcomeerror"));
    		
		}
    	
    	
	}else{
	
        //it is a new welcome object
    	if ($container_guid){
        	
    		$welcome = new ElggObject();
    		// Tell the system it's a pages welcome message
    		$welcome->subtype = "pages_welcome";
    		$welcome->title = "Welcome";
    		$welcome->description = $message;
    		$welcome->access_id = $access_id;
    		
    		// Set the owner
    		$welcome->container_guid = $container_guid;
    		
    	    // save
    		if (!$welcome->save()){
    			register_error(elgg_echo("pages:welcomeerror"));
    		} else {
        		system_message(elgg_echo("pages:welcomeposted"));
    		}
    
    		
    	} else {
        	
        	register_error(elgg_echo("pages:welcomeerror"));
        	
    	}
    	
	}//end of first if statement
    	
	forward("pg/pages/owned/" . get_entity($container_guid)->username);

?>