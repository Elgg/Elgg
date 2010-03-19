<?php
/**
 * Elgg Bookmarks plugin
 *
 * @package ElggBookmarks
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

// Bookmarks initialisation function
function bookmarks_init() {
	// Grab the config global
	global $CONFIG;

	//add a tools menu option
	add_menu(elgg_echo('bookmarks'), $CONFIG->wwwroot . 'mod/bookmarks/all.php');

	// Register a page handler, so we can have nice URLs
	register_page_handler('bookmarks', 'bookmarks_page_handler');

	// Add our CSS
	elgg_extend_view('css', 'bookmarks/css');

	// Register granular notification for this type
	if (is_callable('register_notification_object')) {
		register_notification_object('object', 'bookmarks', elgg_echo('bookmarks:new'));
	}

	// Listen to notification events and supply a more useful message
	register_plugin_hook('notify:entity:message', 'object', 'bookmarks_notify_message');

	// Register a URL handler for shared items
	register_entity_url_handler('bookmark_url','object','bookmarks');

	// Shares widget
	add_widget_type('bookmarks',elgg_echo("bookmarks:recent"),elgg_echo("bookmarks:widget:description"));

	// Register entity type
	register_entity_type('object','bookmarks');

	// Add group menu option
	add_group_tool_option('bookmarks',elgg_echo('bookmarks:enablebookmarks'),true);

}

/**
 * Sidebar menu for bookmarks
 *
 */
function bookmarks_pagesetup() {
	global $CONFIG;

	// Set up menu for logged in users
	// add submenu options - @todo partially removed - now provided by drop-down menu filter in content area
	if (get_context() == "bookmarks") {
/*
		if (isloggedin()) {
			if (page_owner()) {
				$page_owner = page_owner_entity();
				add_submenu_item(elgg_echo('bookmarks:read'),$CONFIG->wwwroot."pg/bookmarks/" . $page_owner->username . "/items");
			}
			if(!$page_owner instanceof ElggGroup)
				add_submenu_item(elgg_echo('bookmarks:friends'),$CONFIG->wwwroot."pg/bookmarks/" . $_SESSION['user']->username . "/friends");
			}

			if(!$page_owner instanceof ElggGroup)
				add_submenu_item(elgg_echo('bookmarks:everyone'),$CONFIG->wwwroot."mod/bookmarks/everyone.php");
*/

			// Bookmarklet
			if ((isloggedin()) && (page_owner()) && (can_write_to_container(0, page_owner()))) {
				$page_owner = page_owner_entity();
				$bmtext = elgg_echo('bookmarks:bookmarklet');
				if ($page_owner instanceof ElggGroup)
					$bmtext = elgg_echo('bookmarks:bookmarklet:group');
				// add_submenu_item($bmtext, $CONFIG->wwwroot . "pg/bookmarks/{$page_owner->username}/bookmarklet");
			}
		}

		$page_owner = page_owner_entity();

		if ($page_owner instanceof ElggGroup && get_context() == 'groups') {
			if($page_owner->bookmarks_enable != "no"){
			add_submenu_item(sprintf(elgg_echo("bookmarks:group"),$page_owner->name), $CONFIG->wwwroot . "pg/bookmarks/" . $page_owner->username . '/items');
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

	// The first component of a bookmarks URL is the username
	if (isset($page[0])) {
		set_input('username',$page[0]);
	}

	// The second part dictates what we're doing
	if (isset($page[1])) {
		switch($page[1]) {
			case "friends":
				include(dirname(__FILE__) . "/friends.php");
				return true;
				break;
			case "items":
				include(dirname(__FILE__) . "/index.php");
				return true;
				break;
			case "add":
				include(dirname(__FILE__) . "/add.php");
				return true;
				break;
			case "edit":
				set_input('bookmark',$page[2]);
				include(dirname(__FILE__) . "/add.php");
				return true;
				break;
			case "bookmarklet":
				include(dirname(__FILE__) . "/bookmarklet.php");
				return true;
				break;
		}
		// If the URL is just 'bookmarks/username', or just 'bookmarks/', load the standard bookmarks index
	} else {
		include(dirname(__FILE__) . "/index.php");
		return true;
	}

	return false;

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
	$title = friendly_title($title);
	return $CONFIG->url . "pg/bookmarks/" . $entity->getOwnerEntity()->username . "/read/" . $entity->getGUID() . "/" . $title;

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

/**
 * A function to generate an internal code to put on the wire in place of the full url
 * to save space.
 **/

function create_wire_url_code(){
	$chars = "abcdefghijkmnopqrstuvwxyz023456789";
	srand((double)microtime()*1000000);
	$i = 0;
	$code = '';

	while ($i <= 4) {
		$num = rand() % 33;
		$tmp = substr($chars, $num, 1);
		$code = $code . $tmp;
		$i++;
	}
	$code = "{{L:" . $code . "}}";
	return $code;
}

// Make sure the initialisation function is called on initialisation
register_elgg_event_handler('init','system','bookmarks_init');
register_elgg_event_handler('pagesetup','system','bookmarks_pagesetup');

// Register actions
global $CONFIG;
register_action('bookmarks/add',false,$CONFIG->pluginspath . "bookmarks/actions/add.php");
register_action('bookmarks/edit',false,$CONFIG->pluginspath . "bookmarks/actions/edit.php");
register_action('bookmarks/delete',false,$CONFIG->pluginspath . "bookmarks/actions/delete.php");
register_action('bookmarks/reference',false,$CONFIG->pluginspath . "bookmarks/actions/reference.php");
register_action('bookmarks/remove',false,$CONFIG->pluginspath . "bookmarks/actions/remove.php");
