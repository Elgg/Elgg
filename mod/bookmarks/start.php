<?php

/**
 * Elgg Bookmarks plugin
 *
 * @package ElggBookmarks
 */

// Bookmarks initialisation function
function bookmarks_init() {

	// Grab the config global
	global $CONFIG;

	//add a tools menu option
	if (isloggedin()) {
		add_menu(elgg_echo('bookmarks'), $CONFIG->wwwroot . "pg/bookmarks/owner/" . $_SESSION['user']->username);

		// add "bookmark this" to owner block
		elgg_extend_view('owner_block/extend', 'bookmarks/owner_block');
	}

	// Register a page handler, so we can have nice URLs
	register_page_handler('bookmarks','bookmarks_page_handler');

	// Add our CSS
	elgg_extend_view('css','bookmarks/css');

	// Register granular notification for this type
	if (is_callable('register_notification_object')) {
		register_notification_object('object', 'bookmarks', elgg_echo('bookmarks:new'));
	}

	// Listen to notification events and supply a more useful message
	register_plugin_hook('notify:entity:message', 'object', 'bookmarks_notify_message');

	// Register a URL handler for shared items
	register_entity_url_handler('bookmark_url','object','bookmarks');

	// Shares widget
	add_widget_type('bookmarks',elgg_echo("bookmarks"),elgg_echo("bookmarks:widget:description"));

	// Register entity type
	register_entity_type('object','bookmarks');

	// Add group menu option
	add_group_tool_option('bookmarks',elgg_echo('groups:enablebookmarks'),true);
	elgg_extend_view('groups/right_column', 'bookmarks/groupprofile_bookmarks');
}

/**
 * Sidebar menu for bookmarks
 *
 */
function bookmarks_pagesetup() {
	global $CONFIG;

	$page_owner = page_owner_entity();

	//add submenu options
	if (get_context() == "bookmarks") {

		if (isloggedin()) {
			// link to add bookmark form
			if ($page_owner instanceof ElggGroup) {
				if ($page_owner->isMember(get_loggedin_user())) {
					add_submenu_item(elgg_echo('bookmarks:add'), $CONFIG->wwwroot."pg/bookmarks/add/" . $page_owner->username);
				}
			} else {
				add_submenu_item(elgg_echo('bookmarks:add'), $CONFIG->wwwroot."pg/bookmarks/add/" . $_SESSION['user']->username);
				add_submenu_item(elgg_echo('bookmarks:inbox'),$CONFIG->wwwroot."pg/bookmarks/inbox/" . $_SESSION['user']->username);
			}
			if (page_owner()) {
				add_submenu_item(sprintf(elgg_echo('bookmarks:read'), $page_owner->name),$CONFIG->wwwroot."pg/bookmarks/owner/" . $page_owner->username);
			}
			if (!$page_owner instanceof ElggGroup) {
				add_submenu_item(elgg_echo('bookmarks:friends'),$CONFIG->wwwroot."pg/bookmarks/friends/" . $_SESSION['user']->username);
			}
		}

		if (!$page_owner instanceof ElggGroup) {
			add_submenu_item(elgg_echo('bookmarks:everyone'),$CONFIG->wwwroot."pg/bookmarks/all/");
		}
		
		// Bookmarklet
		if ((isloggedin()) && (page_owner()) && (can_write_to_container(0, page_owner()))) {

			$bmtext = elgg_echo('bookmarks:bookmarklet');
			if ($page_owner instanceof ElggGroup) {
				$bmtext = elgg_echo('bookmarks:bookmarklet:group');
			}
			add_submenu_item($bmtext, $CONFIG->wwwroot . "pg/bookmarks/bookmarklet/{$page_owner->username}/");
		}

	}

	if ($page_owner instanceof ElggGroup && get_context() == 'groups') {
		if ($page_owner->bookmarks_enable != "no") {
			add_submenu_item(sprintf(elgg_echo("bookmarks:group"),$page_owner->name), $CONFIG->wwwroot . "pg/bookmarks/owner/" . $page_owner->username);
		}
	}

}

/**
 * Bookmarks page handler; allows the use of fancy URLs
 *
 * @param array $page From the page_handler function
 * @return true|false Depending on success
 */
function bookmarks_page_handler($page) {

	// group usernames
	if (substr_count($page[0], 'group:')) {
		preg_match('/group\:([0-9]+)/i', $page[0], $matches);
		$guid = $matches[1];
		if ($entity = get_entity($guid)) {
			bookmarks_url_forwarder($page);
		}
	}

	// user usernames
	$user = get_user_by_username($page[0]);
	if ($user) {
		bookmarks_url_forwarder($page);
	}

	switch ($page[0]) {
		case "read":
			set_input('guid', $page[1]);
			require(dirname(dirname(dirname(__FILE__))) . "/entities/index.php");
			break;
		case "friends":
			set_input('username', $page[1]);
			include(dirname(__FILE__) . "/friends.php");
			break;
		case "all":
			include(dirname(__FILE__) . "/everyone.php");
			break;
		case "inbox":
			set_input('username', $page[1]);
			include(dirname(__FILE__) . "/inbox.php");
			break;
		case "owner":
			set_input('username', $page[1]);
			include(dirname(__FILE__) . "/index.php");
			break;
		case "add":
			set_input('username', $page[1]);
			include(dirname(__FILE__) . "/add.php");
			break;
		case "edit":
			set_input('bookmark', $page[1]);
			include(dirname(__FILE__) . "/add.php");
			break;
		case "bookmarklet":
			set_input('username', $page[1]);
			include(dirname(__FILE__) . "/bookmarklet.php");
			break;
		default:
			return false;
	}

	return true;
}

/**
 * Forward to the new style of URLs
 *
 * @param string $page
 */
function bookmarks_url_forwarder($page) {
	global $CONFIG;

	if (!isset($page[1])) {
		$page[1] = 'items';
	}

	switch ($page[1]) {
		case "read":
			$url = "{$CONFIG->wwwroot}pg/bookmarks/read/{$page[2]}/{$page[3]}";
			break;
		case "inbox":
			$url = "{$CONFIG->wwwroot}pg/bookmarks/inbox/{$page[0]}/";
			break;
		case "friends":
			$url = "{$CONFIG->wwwroot}pg/bookmarks/friends/{$page[0]}/";
			break;
		case "add":
			$url = "{$CONFIG->wwwroot}pg/bookmarks/add/{$page[0]}/";
			break;
		case "items":
			$url = "{$CONFIG->wwwroot}pg/bookmarks/owner/{$page[0]}/";
			break;
		case "bookmarklet":
			$url = "{$CONFIG->wwwroot}pg/bookmarks/bookmarklet/{$page[0]}/";
			break;
	}

	register_error(elgg_echo("changebookmark"));
	forward($url);
}

/**
 * Populates the ->getUrl() method for bookmarked objects
 *
 * @param ElggEntity $entity The bookmarked object
 * @return string bookmarked item URL
 */
function bookmark_url($entity) {

	global $CONFIG;
	$title = $entity->title;
	$title = elgg_get_friendly_title($title);
	return $CONFIG->url . "pg/bookmarks/read/" . $entity->getGUID() . "/" . $title;
}

/**
 * Returns a more meaningful message
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $returnvalue
 * @param unknown_type $params
 */
function bookmarks_notify_message($hook, $entity_type, $returnvalue, $params) {
	$entity = $params['entity'];
	$to_entity = $params['to_entity'];
	$method = $params['method'];
	if (($entity instanceof ElggEntity) && ($entity->getSubtype() == 'bookmarks')) {
		$descr = $entity->description;
		$title = $entity->title;
		global $CONFIG;
		$url = $CONFIG->wwwroot . "pg/view/" . $entity->guid;
		if ($method == 'sms') {
			$owner = $entity->getOwnerEntity();
			return $owner->name . ' ' . elgg_echo("bookmarks:via") . ': ' . $url . ' (' . $title . ')';
		}
		if ($method == 'email') {
			$owner = $entity->getOwnerEntity();
			return $owner->name . ' ' . elgg_echo("bookmarks:via") . ': ' . $title . "\n\n" . $descr . "\n\n" . $entity->getURL();
		}
		if ($method == 'web') {
			$owner = $entity->getOwnerEntity();
			return $owner->name . ' ' . elgg_echo("bookmarks:via") . ': ' . $title . "\n\n" . $descr . "\n\n" . $entity->getURL();
		}

	}
	return null;
}


// Make sure the initialisation function is called on initialisation
register_elgg_event_handler('init','system','bookmarks_init');
register_elgg_event_handler('pagesetup','system','bookmarks_pagesetup');

// Register actions
global $CONFIG;
register_action('bookmarks/add',false,$CONFIG->pluginspath . "bookmarks/actions/add.php");
register_action('bookmarks/delete',false,$CONFIG->pluginspath . "bookmarks/actions/delete.php");

?>