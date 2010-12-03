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
	add_menu(elgg_echo('bookmarks'), 'pg/bookmarks');

	// Register a page handler, so we can have nice URLs
	register_page_handler('bookmarks', 'bookmarks_page_handler');

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
	add_widget_type('bookmarks',elgg_echo("bookmarks"),elgg_echo("bookmarks:widget:description"));

	// Register entity type
	register_entity_type('object','bookmarks');

	// Add group menu option
	add_group_tool_option('bookmarks',elgg_echo('bookmarks:enablebookmarks'),true);

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

	$page_owner = elgg_get_page_owner();

	// Add group bookmark menu item
	if (isloggedin()) {
		if ($page_owner instanceof ElggGroup && elgg_get_context() == 'groups') {
			if ($page_owner->bookmarks_enable != "no") {
				//add_submenu_item(elgg_echo("bookmarks:group",array($page_owner->name), "pg/bookmarks/" . $page_owner->username . '/items'));
			}
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

	// The first component of a bookmarks URL is the username
	// If the username is set_input()'d and has group:NN in it, magic happens
	// and the elgg_get_page_owner() is the group.
	if (isset($page[0])) {
		$owner_name = $page[0];
		set_input('username', $owner_name);

		// grab the page owner here so the group magic works.
		$owner = elgg_get_page_owner();
	} else {
		set_page_owner(get_loggedin_userid());
	}

	// owner name passed but invalid.
	if ($owner_name && !$owner) {
		$sidebar = elgg_view('bookmarks/sidebar', array('object_type' => 'bookmarks'));
		$content = elgg_echo("bookmarks:unknown_user");

		$params = array(
			'content' => $content,
			'sidebar' => $sidebar,
		);
		$body = elgg_view_layout('one_column_with_sidebar', $params);
		echo elgg_view_page(elgg_echo("bookmarks:user", array(elgg_get_page_owner()->name)), $body);

		return FALSE;
	}

	$logged_in_user = get_loggedin_user();
	$section = (isset($page[1])) ? $page[1] : $section = 'items';

	//don't show the all site bookmarks breadcrumb when on the all site bookmarks page
	if(elgg_get_page_owner_guid() != 0){
		elgg_push_breadcrumb(elgg_echo('bookmarks:all'), $CONFIG->wwwroot . 'pg/bookmarks/');
	}

	if ($owner) {
		switch($section) {
			case 'friends':
				elgg_push_breadcrumb(elgg_echo('bookmarks:friends', array($owner->name)));

				$content = list_user_friends_objects($owner->getGUID(), 'bookmarks', 10, false, false);
				$context = ($owner == $logged_in_user) ? 'friends' : '';
				break;

			default:
			case 'items':
				elgg_push_breadcrumb(elgg_echo('bookmarks:user', array($owner->name)));

				group_gatekeeper();
				$options = array(
					'type' => 'object',
					'subtype' => 'bookmarks'
				);

				if ($owner instanceof ElggGroup) {
					$options['container_guid'] = $owner->getGUID();
				} else {
					$options['owner_guid'] = $owner->getGUID();
				}

				$content = elgg_list_entities($options);

				if (!$content && ($owner == $logged_in_user)) {
					$content = elgg_view('help/bookmarks');
				}

				$context = ($owner == $logged_in_user) ? 'mine' : '';
				break;

			case 'add':
				gatekeeper();
				elgg_push_breadcrumb(elgg_echo('bookmarks:add'));

				$vars = array();
				if ($owner instanceof ElggGroup) {
					$vars['container_guid'] = $owner->getGUID();
				}

				$context = 'action';
				$content = elgg_view('bookmarks/form', $vars);
				break;

			case 'edit':
				gatekeeper();

				elgg_push_breadcrumb(elgg_echo('bookmarks:edit'));

				$vars = array();
				// this will never be the case.
				if ($owner instanceof ElggGroup) {
					$vars['container_guid'] = $owner->getGUID();
				}

				$bookmark = (isset($page[2])) ? get_entity($page[2]) : FALSE;

				if ($bookmark && elgg_instanceof($bookmark, 'object', 'bookmarks') && $bookmark->canEdit()) {
					$vars['entity'] = $bookmark;
					$context = 'action';
					$content = elgg_view('bookmarks/form', $vars);
				} else {
					$content = elgg_echo('bookmarks:cannot_find_bookmark');
				}

				break;

			// I don't think this is used.
			case 'bookmarklet':
				gatekeeper();

				$content = elgg_view_title(elgg_echo('bookmarks:bookmarklet'));
				$content .= elgg_view('bookmarks/bookmarklet');

				break;
		}

	} else {
		// no owner name passed, show everything.
		$content = elgg_list_entities(array('type' => 'object', 'subtype' => 'bookmarks'));
		$context = 'everyone';
	}

	// sidebar
	if ($logged_in_user != $owner) {
		$area3 = elgg_view('bookmarks/ownerblock');
	}

	$sidebar = elgg_view('bookmarks/sidebar', array('object_type' => 'bookmarks'));

	if (isloggedin()){
		$sidebar .= elgg_view('bookmarks/bookmarklet');
	}

	// main content
	//if ($owner != $logged_in_user || $context == 'action') {
	$header = elgg_view('navigation/breadcrumbs');
	//}
	//if no user is set
	if(!$owner_name){
		$owner_name = get_loggedin_user()->username;
	}

	//select the header depending on whether a user is looking at their bookmarks or someone elses
	if($owner){
		if ($owner != $logged_in_user && !($owner instanceof ElggGroup)) {
			$header .= elgg_view("page_elements/content_header_member", array(
				'type' => 'bookmarks'
			));
		}else{
			$header .= elgg_view("page_elements/content_header", array(
				'context' => $context,
				'type' => 'bookmarks',
				'all_link' => "pg/bookmarks/",
				'new_link' => "pg/bookmarks/{$owner_name}/add"
			));
		}
	}else{
		$header .= elgg_view("page_elements/content_header", array(
				'context' => $context,
				'type' => 'bookmarks',
				'all_link' => "pg/bookmarks/",
				'new_link' => "pg/bookmarks/{$owner_name}/add"
			));
	}

	$content = $header . $content;
	$params = array(
		'content' => $content,
		'sidebar' => $sidebar,
	);
	$body = elgg_view_layout('one_column_with_sidebar', $params);
	echo elgg_view_page(elgg_echo("bookmarks:user", array(elgg_get_page_owner()->name)), $body);

	return TRUE;
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
	return "pg/bookmarks/" . $entity->getOwnerEntity()->username . "/read/" . $entity->getGUID() . "/" . $title;
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

function bookmarks_profile_menu($hook, $entity_type, $return_value, $params) {
	global $CONFIG;

	$return_value[] = array(
		'text' => elgg_echo('bookmarks'),
		'href' => "pg/bookmarks/{$params['owner']->username}",
	);

	return $return_value;
}

// Make sure the initialisation function is called on initialisation
elgg_register_event_handler('init','system','bookmarks_init');
elgg_register_event_handler('pagesetup','system','bookmarks_pagesetup');

// Register actions
global $CONFIG;
elgg_register_action('bookmarks/add', $CONFIG->pluginspath . "bookmarks/actions/add.php");
elgg_register_action('bookmarks/edit', $CONFIG->pluginspath . "bookmarks/actions/edit.php");
elgg_register_action('bookmarks/delete', $CONFIG->pluginspath . "bookmarks/actions/delete.php");
elgg_register_action('bookmarks/reference', $CONFIG->pluginspath . "bookmarks/actions/reference.php");
elgg_register_action('bookmarks/remove', $CONFIG->pluginspath . "bookmarks/actions/remove.php");
