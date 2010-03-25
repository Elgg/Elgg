<?php

/**
 * Elgg bookmarks remove a reference
 * Guid one is the object, guid two is the bookmark, 'reference' is the relationship name
 */

//set some variables
$object_guid = get_input('object_guid');
$bookmark_guid = get_input('bookmark');
$object = get_entity($object_guid);
$bookmark = get_entity($bookmark_guid);
//check both the object and bookmark exist
if($bookmark && $object){
	//check the user can add a reference
	if($object->canEdit()){
		//remove the relationship between the object and bookmark
		if(remove_entity_relationship($object_guid, "reference", $bookmark_guid)){
			// Success message
			system_message(elgg_echo("bookmarks:removed"));
		}else{
			// Failure message
			system_message(elgg_echo("bookmarks:removederror"));
		}	
	}else{
		// Failure message
		system_message(elgg_echo("bookmarks:removederror"));
	}
}else{
	// Failure message
	system_message(elgg_echo("bookmarks:removederror"));
}

forward($object->getURL());