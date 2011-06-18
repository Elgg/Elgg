<?php
/**
 * Elgg Bookmarks plugin
 *
 * @package ElggBookmarks
 */

elgg_register_event_handler('init', 'system', 'bookmarks_init');

/**
 * Bookmark init
 */
function bookmarks_init() {

	$root = dirname(__FILE__);
	elgg_register_library('elgg:bookmarks', "$root/lib/bookmarks.php");

	// actions
	$action_path = "$root/actions/bookmarks";
	elgg_register_action('bookmarks/save', "$action_path/save.php");
	elgg_register_action('bookmarks/delete', "$action_path/delete.php");
	elgg_register_action('bookmarks/share', "$action_path/share.php");

	// menus
	elgg_register_menu_item('site', array(
		'name' => 'bookmarks',
		'text' => elgg_echo('bookmarks'),
		'href' => 'bookmarks/all'
	));

	elgg_register_plugin_hook_handler('register', 'menu:page', 'bookmarks_page_menu');
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'bookmarks_owner_block_menu');

	elgg_register_page_handler('bookmarks', 'bookmarks_page_handler');

	elgg_extend_view('css/elgg', 'bookmarks/css');
	elgg_extend_view('js/elgg', 'bookmarks/js');

	elgg_register_widget_type('bookmarks', elgg_echo('bookmarks'), elgg_echo('bookmarks:widget:description'));

	if (elgg_is_logged_in()) {
		$user_guid = elgg_get_logged_in_user_guid();
		$address = urlencode(current_page_url());

		elgg_register_menu_item('extras', array(
			'name' => 'bookmark',
			'text' => elgg_view_icon('push-pin-alt'),
			'href' => "bookmarks/add/$user_guid?address=$address",
			'title' => elgg_echo('bookmarks:this'),
			'rel' => 'nofollow',
		));
	}
	// Register granular notification for this type
	register_notification_object('object', 'bookmarks', elgg_echo('bookmarks:new'));

	// Listen to notification events and supply a more useful message
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'bookmarks_notify_message');

	// Register a URL handler for bookmarks
	elgg_register_entity_url_handler('object', 'bookmarks', 'bookmark_url');

	// Register entity type for search
	elgg_register_entity_type('object', 'bookmarks');

	// Groups
	add_group_tool_option('bookmarks', elgg_echo('bookmarks:enablebookmarks'), true);
	elgg_extend_view('groups/tool_latest', 'bookmarks/group_module');
}

/**
 * Dispatcher for bookmarks.
 *
 * URLs take the form of
 *  All bookmarks:        bookmarks/all
 *  User's bookmarks:     bookmarks/owner/<username>
 *  Friends' bookmarks:   bookmarks/friends/<username>
 *  View bookmark:        bookmarks/view/<guid>/<title>
 *  New bookmark:         bookmarks/add/<guid> (container: user, group, parent)
 *  Edit bookmark:        bookmarks/edit/<guid>
 *  Group bookmarks:      bookmarks/group/<guid>/all
 *  Bookmarklet:          bookmarks/bookmarklet/<guid> (user)
 *
 * Title is ignored
 *
 * @param array $page
 */
function bookmarks_page_handler($page) {
	elgg_load_library('elgg:bookmarks');

	elgg_push_breadcrumb(elgg_echo('bookmarks'), 'bookmarks/all');
	elgg_push_context('bookmarks');

	// old group usernames
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

	$pages = dirname(__FILE__) . '/pages/bookmarks';

	switch ($page[0]) {
		case "all":
			include "$pages/all.php";
			break;

		case "owner":
			include "$pages/owner.php";
			break;

		case "friends":
			include "$pages/friends.php";
			break;

		case "read":
		case "view":
			set_input('guid', $page[1]);
			include "$pages/view.php";
			break;

		case "add":
			gatekeeper();
			include "$pages/add.php";
			break;

		case "edit":
			gatekeeper();
			set_input('guid', $page[1]);
			include "$pages/edit.php";
			break;

		case 'group':
			group_gatekeeper();
			include "$pages/owner.php";
			break;

		case "bookmarklet":
			set_input('container_guid', $page[1]);
			include "$pages/bookmarklet.php";
			break;

		default:
			return false;
	}

	elgg_pop_context();

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
			$url = "{$CONFIG->wwwroot}bookmarks/view/{$page[2]}/{$page[3]}";
			break;
		case "inbox":
			$url = "{$CONFIG->wwwroot}bookmarks/inbox/{$page[0]}";
			break;
		case "friends":
			$url = "{$CONFIG->wwwroot}bookmarks/friends/{$page[0]}";
			break;
		case "add":
			$url = "{$CONFIG->wwwroot}bookmarks/add/{$page[0]}";
			break;
		case "items":
			$url = "{$CONFIG->wwwroot}bookmarks/owner/{$page[0]}";
			break;
		case "bookmarklet":
			$url = "{$CONFIG->wwwroot}bookmarks/bookmarklet/{$page[0]}";
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
	return $CONFIG->url . "bookmarks/view/" . $entity->getGUID() . "/" . $title;
}

/**
 * Add a menu item to an ownerblock
 * 
 * @param string $hook
 * @param string $type
 * @param array  $return
 * @param array  $params
 */
function bookmarks_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "bookmarks/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('bookmarks', elgg_echo('bookmarks'), $url);
		$return[] = $item;
	} else {
		if ($params['entity']->bookmarks_enable != 'no') {
			$url = "bookmarks/group/{$params['entity']->guid}/all";
			$item = new ElggMenuItem('bookmarks', elgg_echo('bookmarks:group'), $url);
			$return[] = $item;
		}
	}

	return $return;
}

/**
 * Returns the body of a notification message
 *
 * @param string $hook
 * @param string $entity_type
 * @param string $returnvalue
 * @param array  $params
 */
function bookmarks_notify_message($hook, $entity_type, $returnvalue, $params) {
	$entity = $params['entity'];
	$to_entity = $params['to_entity'];
	$method = $params['method'];
	if (($entity instanceof ElggEntity) && ($entity->getSubtype() == 'bookmarks')) {
		$descr = $entity->description;
		$title = $entity->title;
		global $CONFIG;
		$url = elgg_get_site_url() . "view/" . $entity->guid;
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
 * Add a page menu menu.
 *
 * @param string $hook
 * @param string $type
 * @param array  $return
 * @param array  $params
 */
function bookmarks_page_menu($hook, $type, $return, $params) {
	if (elgg_is_logged_in()) {
		// only show bookmarklet in bookmark pages
		if (elgg_in_context('bookmarks')) {
			$page_owner = elgg_get_page_owner_entity();
			if (!$page_owner) {
				$page_owner = elgg_get_logged_in_user_entity();
			}
			
			if ($page_owner instanceof ElggGroup) {
				$title = elgg_echo('bookmarks:bookmarklet:group');
			} else {
				$title = elgg_echo('bookmarks:bookmarklet');
			}

			$return[] = new ElggMenuItem('bookmarklet', $title, 'bookmarks/bookmarklet/' . $page_owner->getGUID());
		}
	}

	return $return;
}
