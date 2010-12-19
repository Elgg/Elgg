<?php
/**
 * Blogs
 *
 * @package Blog
 *
 * @todo
 * Either drop support for "publish date" or duplicate more entity getter
 * functions to work with a non-standard time_created.
 * Show friends blog posts
 * Widget
 * Pingbacks
 * Notifications
 */

elgg_register_event_handler('init', 'system', 'blog_init');

/**
 * Init blog plugin.
 */
function blog_init() {
	
	elgg_register_library('elgg:blog', elgg_get_plugin_path() . 'blog/lib/blog.php');

	$item = new ElggMenuItem('blog', elgg_echo('blog:blogs'), 'pg/blog/all');
	elgg_register_menu_item('site', $item);

	// run the setup upon activations or to upgrade old installations.
	run_function_once('blog_runonce', '1269370108');

	elgg_extend_view('css/screen', 'blog/css');

	elgg_register_event_handler('pagesetup', 'system', 'blog_page_setup');
	register_page_handler('blog', 'blog_page_handler');
	register_entity_url_handler('blog_url_handler', 'object', 'blog');

	// notifications
	register_notification_object('object', 'blog', elgg_echo('blog:newpost'));
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'blog_notify_message');

	elgg_register_plugin_hook_handler('register', 'menu:user_ownerblock', 'blog_user_ownerblock_menu');

	// pingbacks
	//elgg_register_event_handler('create', 'object', 'blog_incoming_ping');
	//elgg_register_plugin_hook_handler('pingback:object:subtypes', 'object', 'blog_pingback_subtypes');

	// Register for search.
	register_entity_type('object', 'blog');

	//elgg_register_widget_type('blog', elgg_echo('blog'), elgg_echo('blog:widget:description'), 'profile, dashboard');

	// register actions
	$action_path = elgg_get_plugin_path() . 'blog/actions/blog';
	elgg_register_action('blog/save', "$action_path/save.php");
	elgg_register_action('blog/auto_save_revision', "$action_path/auto_save_revision.php");
	elgg_register_action('blog/delete', "$action_path/delete.php");

	// ecml
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'blog_ecml_views_hook');

	// Register profile menu hook
	elgg_register_plugin_hook_handler('profile_menu', 'profile', 'blog_profile_menu');
}

/**
 * Register entity class for object:blog -> ElggBlog
 */
function blog_runonce() {
	if (!update_subtype('object', 'blog', 'ElggBlog')) {
		add_subtype('object', 'blog', 'ElggBlog');
	}
}

/**
 * Dispatches blog pages.
 * URLs take the form of
 * 	pg/blog/[all|owner|read|edit|archive|new]/[username]/[time_start|guid]/[time_end|title]
 *
 * Without an action, show all blogs
 * Without a guid, show all post for that user.
 * Title is ignored
 *
 * If archive, uses time_start/end
 *
 * @todo no archives for all blogs or friends
 *
 * @param array $page
 * @return NULL
 */
function blog_page_handler($page) {

	// @todo remove the forwarder in 1.9
	// forward to correct URL for bookmaarks pre-1.7.5
	// group usernames
	if (substr_count($page[0], 'group:')) {
		preg_match('/group\:([0-9]+)/i', $page[0], $matches);
		$guid = $matches[1];
		if ($entity = get_entity($guid)) {
			blog_url_forwarder($page);
		}
	}
	// user usernames
	$user = get_user_by_username($page[0]);
	if ($user) {
		blog_url_forwarder($page);
	}

	elgg_load_library('elgg:blog');

	// push breadcrumb
	elgg_push_breadcrumb(elgg_echo('blog:blogs'), "pg/blog/all/");

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	// if username not set, we are showing all blog posts
	if (!isset($page[1])) {
		$title = elgg_echo('blog:title:all_blogs');
		$params = blog_get_page_content_list();
	} else {
		$username = $page[1];
		// forward away if invalid user.
		if (!$user = get_user_by_username($username)) {
			register_error('blog:error:unknown_username');
			forward(REFERER);
		}

		set_page_owner($user->getGUID());
		$crumbs_title = elgg_echo('blog:owned_blogs', array($user->name));
		$crumbs_url = "pg/blog/owner/$username/";
		elgg_push_breadcrumb($crumbs_title, $crumbs_url);

		$action = $page[0];
		// yeah these are crap names, but they're used for different things.
		$page2 = isset($page[2]) ? $page[2] : FALSE;
		$page3 = isset($page[3]) ? $page[3] : FALSE;

		switch ($action) {
			case 'owner':
				$title = elgg_echo('blog:title:user_blogs', array($user->name));
				$params = blog_get_page_content_list($user->getGUID());
				break;
			
			case 'read':
				$title = elgg_echo('blog:title:user_blogs', array($user->name));
				$params = blog_get_page_content_read($page2);
				break;

			case 'new':
			case 'edit':
				gatekeeper();
				$title = elgg_echo('blog:edit');
				$params = blog_get_page_content_edit($page2, $page3);
				break;

			case 'archive':
				$title = elgg_echo('blog:archives');
				$params = blog_get_page_content_archive($user->getGUID(), $page2, $page3);
				break;

			case 'friends':
				$title = elgg_echo('blog:title:friends');
				$params = blog_get_page_content_friends($user->getGUID());
				break;

			default:
				forward("pg/blog/owner/$username/");
				break;
		}
	}

	$sidebar_menu = elgg_view('blog/sidebar_menu', array(
		'page' => $action,
	));

	$params['sidebar'] .= $sidebar_menu;

	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * Forward to the new style of URLs
 *
 * @param string $page
 */
function blog_url_forwarder($page) {
	global $CONFIG;

	if (!isset($page[1])) {
		$page[1] = 'owner';
	}

	switch ($page[1]) {
		case "read":
			$url = "{$CONFIG->wwwroot}pg/blog/read/{$page[2]}/{$page[3]}";
			break;
		case "archive":
			$url = "{$CONFIG->wwwroot}pg/blog/archive/{$page[0]}/{$page[2]}/{$page[3]}";
			break;
		case "friends":
			$url = "{$CONFIG->wwwroot}pg/blog/friends/{$page[0]}/";
			break;
		case "new":
			$url = "{$CONFIG->wwwroot}pg/blog/new/{$page[0]}/";
			break;
		case "owner":
			$url = "{$CONFIG->wwwroot}pg/blog/owner/{$page[0]}/";
			break;
	}

	register_error(elgg_echo("changebookmark"));
	forward($url);
}

/**
 * Format and return the correct URL for blogs.
 *
 * @param ElggObject $entity
 * @return string URL of blog.
 */
function blog_url_handler($entity) {
	if (!$user = get_entity($entity->owner_guid)) {
		// default to a standard view if no owner.
		return FALSE;
	}

	$friendly_title = elgg_get_friendly_title($entity->title);

	return "pg/blog/read/{$user->username}/{$entity->guid}/$friendly_title";
}

/**
 * Add a menu item to the user ownerblock
 */
function blog_user_ownerblock_menu($hook, $type, $return, $params) {
	$item = new ElggMenuItem('blog', elgg_echo('blog'), "pg/blog/owner/{$params['user']->username}");
	elgg_register_menu_item('user_ownerblock', $item);
}

/**
 * Register blogs with ECML.
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $return_value
 * @param unknown_type $params
 */
function blog_ecml_views_hook($hook, $entity_type, $return_value, $params) {
	$return_value['object/blog'] = elgg_echo('blog:blogs');

	return $return_value;
}

function blog_profile_menu($hook, $entity_type, $return_value, $params) {

	if (!($params['owner'] instanceof ElggGroup)) {
		$return_value[] = array(
			'text' => elgg_echo('blog'),
			'href' => "pg/blog/owner/{$params['owner']->username}/",
		);
	}

	return $return_value;
}
