<?php
/**
 * Elgg Bookmarks plugin
 *
 * @package ElggBookmarks
 */

function bookmarks_init() {
	global $CONFIG;

	//add a tools menu option
	$item = new ElggMenuItem('bookmarks', elgg_echo('bookmarks'), 'pg/bookmarks/all');
	elgg_register_menu_item('site', $item);

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('bookmarks', 'bookmarks_page_handler');

	// Add our CSS
	elgg_extend_view('css/screen', 'bookmarks/css');

	// Register granular notification for this type
	if (is_callable('register_notification_object')) {
		register_notification_object('object', 'bookmarks', elgg_echo('bookmarks:new'));
	}

	// Listen to notification events and supply a more useful message
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'bookmarks_notify_message');

	// Register a URL handler for shared items
	register_entity_url_handler('bookmark_url','object','bookmarks');

	// Shares widget
	elgg_register_widget_type('bookmarks',elgg_echo("bookmarks"),elgg_echo("bookmarks:widget:description"));

	// Register entity type
	register_entity_type('object','bookmarks');

	// Add group menu option
	add_group_tool_option('bookmarks',elgg_echo('bookmarks:enablebookmarks'),true);

	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'bookmarks_owner_block_menu');

	// Extend Groups profile page
	elgg_extend_view('groups/tool_latest','bookmarks/group_bookmarks');

	// Register profile menu hook
	elgg_register_plugin_hook_handler('profile_menu', 'profile', 'bookmarks_profile_menu');
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

		if (elgg_is_logged_in()) {
			// link to add bookmark form
			if ($page_owner instanceof ElggGroup) {
				if ($page_owner->isMember(elgg_get_logged_in_user_entity())) {
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
		if ((elgg_is_logged_in()) && (page_owner()) && (can_write_to_container(0, page_owner()))) {

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
 * Bookmarks page handler
 * Expects URLs like:
 * 	pg/bookmarks/username/[friends||items||add||edit||bookmarklet]
 *
 *
 * @param array $page From the page_handler function
 * @return true|false Depending on success
 */
function bookmarks_page_handler($page) {
	global $CONFIG;

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


	$pages = dirname(__FILE__) . '/pages';

	switch ($page[0]) {
		case "read":
		case "view":
			set_input('guid', $page[1]);
			include "$pages/view.php";
			break;
		case "friends":
			set_input('username', $page[1]);
			include "$pages/friends.php";
			break;
		case "all":
			include "$pages/all.php";
			break;
		case "inbox":
			set_input('username', $page[1]);
			include "$pages/inbox.php";
			break;
		case "owner":
			set_input('username', $page[1]);
			include "$pages/owner.php";
			break;
		case "add":
			set_input('username', $page[1]);
			include "$pages/add.php";
			break;
		case "edit":
			set_input('bookmark', $page[1]);
			include "$pages/add.php";
			break;
		case "bookmarklet":
			set_input('username', $page[1]);
			include "$pages/bookmarklet.php";
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
			$url = "{$CONFIG->wwwroot}pg/bookmarks/view/{$page[2]}/{$page[3]}";
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
	return $CONFIG->url . "pg/bookmarks/view/" . $entity->getGUID() . "/" . $title;
}

/**
 * Add a menu item to an ownerblock
 */
function bookmarks_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "pg/bookmarks/owner/{$params['user']->username}";
		$item = new ElggMenuItem('bookmarks', elgg_echo('bookmarks'), $url);
		$return[] = $item;
	} else {
		if ($params['entity']->bookmarks_enable != "no") {
			$url = "pg/bookmarks/owner/{$params['entity']->username}";
			$item = new ElggMenuItem('bookmarks', elgg_echo('bookmarks:group'), $url);
			$return[] = $item;
		}
	}

	return $return;
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
		$url = elgg_get_site_url() . "pg/view/" . $entity->guid;
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

elgg_register_event_handler('init', 'system', 'bookmarks_init');
elgg_register_event_handler('pagesetup', 'system', 'bookmarks_pagesetup');

// Register actions
$action_path = dirname(__FILE__) . '/actions/bookmarks';

elgg_register_action('bookmarks/add', "$action_path/add.php", 'logged_in');
elgg_register_action('bookmarks/delete', "$action_path/delete.php", 'logged_in');