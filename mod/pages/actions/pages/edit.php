<?php
/**
 * Edit a page
 *
 * @package ElggPages
 */

// Load configuration
global $CONFIG;

elgg_set_context('pages');

//boolean to select correct add to river. It will be new or edit
$which_river = 'new';

// Get group fields
$input = array();
foreach($CONFIG->pages as $shortname => $valuetype) {
	$input[$shortname] = get_input($shortname);
	if ($shortname == 'title') {
		$input[$shortname] = strip_tags($input[$shortname]);
	}
	if ($valuetype == 'tags')
		$input[$shortname] = string_to_tag_array($input[$shortname]);
}

// Get parent
$parent_guid = (int)get_input('parent_guid', 0);

// New or old?
$page = NULL;
$pages_guid = (int)get_input('pages_guid');

if ($pages_guid) {
	$page = get_entity($pages_guid);
	if (!$page->canEdit()) {
		$page = NULL; // if we can't edit it, go no further.
	}

	//select river boolean to edit
	$which_river = 'edit';
} else {
	$page = new ElggObject();
	if (!$parent_guid) {
		$page->subtype = 'page_top';
	} else {
		$page->subtype = 'page';
	}

	// New instance, so set container_guid
	$container_guid = get_input('container_guid', get_loggedin_userid());
	$page->container_guid = $container_guid;

	// cache data in session in case data from form does not validate
	$_SESSION['page_description'] = $input['description'];
	$_SESSION['page_tags'] = get_input('tags');
	$_SESSION['page_read_access'] = (int)get_input('access_id');
	$_SESSION['page_write_access'] = (int)get_input('write_access_id');
}

// Have we got it? Can we edit it?
if ($page instanceof ElggObject) {
	// Save fields - note we always save latest description as both description and annotation
	if (sizeof($input) > 0) {
		foreach($input as $shortname => $value) {
			$page->$shortname = $value;
		}
	}

	if (!$page->title) {
		register_error(elgg_echo("pages:notitle"));

		forward(REFERER);
	}

	$page->access_id = (int)get_input('access_id', ACCESS_PRIVATE);
	$page->write_access_id = (int)get_input('write_access_id', ACCESS_PRIVATE);
	$page->parent_guid = $parent_guid;
	$page->owner_guid = ($page->owner_guid ? $page->owner_guid : get_loggedin_userid());

	if ($page->save()) {

		// Now save description as an annotation
		$page->annotate('page', $page->description, $page->access_id);

		// clear cache
		unset($_SESSION['page_description']);
		unset($_SESSION['page_tags']);
		unset($_SESSION['page_read_access']);
		unset($_SESSION['page_write_access']);

		system_message(elgg_echo("pages:saved"));

		//add to river
		if ($which_river == 'new') {
			add_to_river('river/object/page/create','create',get_loggedin_userid(),$page->guid);
		} else {
			add_to_river('river/object/page/update','update',get_loggedin_userid(),$page->guid);
		}

		// Forward to the user's profile
		forward($page->getUrl());
	} else {
		register_error(elgg_echo('pages:notsaved'));
	}

} else {
	register_error(elgg_echo("pages:noaccess"));
}

// Forward to the user's profile
forward($page->getUrl());