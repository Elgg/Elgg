<?php
/**
 * Elgg bookmarks add/save action
 * 
 * @package ElggBookmarks
 */
	
gatekeeper();
action_gatekeeper();
//set some required variables
$title = strip_tags(get_input('title'));
$address = get_input('address');
$notes = get_input('notes');
$access = get_input('access');
$tags = get_input('tags');
$tagarray = string_to_tag_array($tags);

if (!$title || !$address) {
	register_error(elgg_echo('bookmarks:save:failed'));
	forward(REFERER);
}

// don't allow malicious code.
// put this in a context of a link so HTMLawed knows how to filter correctly.
$xss_test = "<a href=\"$address\"></a>";
if ($xss_test != filter_tags($xss_test)) {
	register_error(elgg_echo('bookmarks:save:failed'));
	forward(REFERER);
}

//create a new bookmark object
$entity = new ElggObject;
$entity->subtype = "bookmarks";
$entity->owner_guid = get_loggedin_userid();
$entity->container_guid = (int)get_input('container_guid', get_loggedin_userid());
$entity->title = $title;
$entity->description = $notes;
$entity->address = $address;
$entity->access_id = $access;
$entity->link_version = create_wire_url_code();//returns a random code in the form {{L:1hp56}}
$entity->tags = $tagarray;
		
if ($entity->save()) {
	system_message(elgg_echo('bookmarks:save:success'));
	//add to river
	add_to_river('river/object/bookmarks/create','create',get_loggedin_userid(),$entity->guid);
} else {
	register_error(elgg_echo('bookmarks:save:failed'));
}
$account = get_entity((int)get_input('container_guid', get_loggedin_userid()));
forward("pg/bookmarks/" . $account->username);