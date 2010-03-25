<?php

/**
 * Elgg bookmarks add as reference
 * Guid one is the object, guid two is the bookmark, 'reference' is the relationship name
 */

//set some variables
$object_guid = get_input('object_guid');
$bookmark_guid = get_input('reference');
$object = get_entity($object_guid);
$bookmark = get_entity($bookmark_guid);
//check both the object and bookmark exist
if($bookmark && $object){
	//check the user can add a reference
	if($object->canEdit()){
		//create a relationship between the object and bookmark
		if(add_entity_relationship($object_guid, "reference", $bookmark_guid)){	
			// Success message
			system_message(elgg_echo("bookmarks:referenceadded"));
		}else{
			// Failure message
			system_message(elgg_echo("bookmarks:referenceerror"));
		}	
	}else{
		// Failure message
		system_message(elgg_echo("bookmarks:referenceerror"));
	}
}else{
	// Failure message
	system_message(elgg_echo("bookmarks:referenceerror"));
}

forward($object->getURL());