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

	// Register for notifications
	elgg_register_notification_event('object', 'bookmarks', array('create'));
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:bookmarks', 'bookmarks_prepare_notification');

	// Register bookmarks view for ecml parsing
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'bookmarks_ecml_views_hook');

	// Register a URL handler for bookmarks
	elgg_register_plugin_hook_handler('entity:url', 'object', 'bookmark_set_url');

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
 * @return bool
 */
function bookmarks_page_handler($page) {

	elgg_load_library('elgg:bookmarks');

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	elgg_push_breadcrumb(elgg_echo('bookmarks'), 'bookmarks/all');

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

		case "view":
			set_input('guid', $page[1]);
			include "$pages/view.php";
			break;

		case "add":
			elgg_gatekeeper();
			include "$pages/add.php";
			break;

		case "edit":
			elgg_gatekeeper();
			set_input('guid', $page[1]);
			include "$pages/edit.php";
			break;

		case 'group':
			elgg_group_gatekeeper();
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
 * Populates the ->getUrl() method for bookmarked objects
 *
 * @param string $hook
 * @param string $type
 * @param string $url
 * @param array  $params
 * @return string bookmarked item URL
 */
function bookmark_set_url($hook, $type, $url, $params) {
	$entity = $params['entity'];
	if (elgg_instanceof($entity, 'object', 'bookmarks')) {
		$title = elgg_get_friendly_title($entity->title);
		return "bookmarks/view/" . $entity->getGUID() . "/" . $title;
	}
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
 * Prepare a notification message about a new bookmark
 * 
 * @param string                          $hook         Hook name
 * @param string                          $type         Hook type
 * @param Elgg\Notifications\Notification $notification The notification to prepare
 * @param array                           $params       Hook parameters
 * @return Elgg\Notifications\Notification
 */
function bookmarks_prepare_notification($hook, $type, $notification, $params) {
	$entity = $params['event']->getObject();
	$owner = $params['event']->getActor();
	$recipient = $params['recipient'];
	$language = $params['language'];
	$method = $params['method'];

	$descr = $entity->description;
	$title = $entity->title;

	$notification->subject = elgg_echo('bookmarks:notify:subject', array($title), $language); 
	$notification->body = elgg_echo('bookmarks:notify:body', array(
		$owner->name,
		$title,
		$entity->address,
		$descr,
		$entity->getURL()
	), $language);
	$notification->summary = elgg_echo('bookmarks:notify:summary', array($entity->title), $language);

	return $notification;
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

/**
 * Return bookmarks views to parse for ecml
 *
 * @param string $hook
 * @param string $type
 * @param array  $return
 * @param array  $params
 */
function bookmarks_ecml_views_hook($hook, $type, $return, $params) {
	$return['object/bookmarks'] = elgg_echo('item:object:bookmarks');
	return $return;
}
