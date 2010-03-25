<?php
/**
 * Elgg bookmarks edit action
 * 
 */
	
gatekeeper();
action_gatekeeper();
//set some required variables
$guid = get_input('guid');
$title = get_input('title');
$address = get_input('address');
$notes = get_input('notes');
$access = get_input('access');
$tags = get_input('tags');
$tagarray = string_to_tag_array($tags);

// Make sure we actually have permission to edit
$bookmark = get_entity($guid);
if ($bookmark->getSubtype() == "bookmarks" && $bookmark->canEdit()) {
	$bookmark->title = $title;
	$bookmark->description = $notes;
	$bookmark->address = $address;
	$bookmark->access_id = $access;
	$bookmark->tags = $tagarray;
	if ($bookmark->save()) {
		system_message(elgg_echo('bookmarks:edit:success'));
	} else {
		system_message(elgg_echo('bookmarks:edit:fail'));
	}
}else{
	system_message(elgg_echo('bookmarks:edit:fail'));
}
$account = get_entity($bookmark->container_guid);
forward("pg/bookmarks/" . $account->username);