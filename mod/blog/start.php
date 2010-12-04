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
 *
 * Pingbacks
 * Notifications
 */

/**
 * Init blog plugin.
 *
 * @return TRUE
 */
function blog_init() {
	global $CONFIG;
	require_once dirname(__FILE__) . '/blog_lib.php';

	add_menu(elgg_echo('blog:blogs'), "pg/blog/", array());

	// run the setup upon activations or to upgrade old installations.
	run_function_once('blog_runonce', '1269370108');

	elgg_extend_view('css/screen', 'blog/css');

	elgg_register_event_handler('pagesetup', 'system', 'blog_page_setup');
	register_page_handler('blog', 'blog_page_handler');
	register_entity_url_handler('blog_url_handler', 'object', 'blog');

	// notifications
	register_notification_object('object', 'blog', elgg_echo('blog:newpost'));
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'blog_notify_message');

	// pingbacks
	//elgg_register_event_handler('create', 'object', 'blog_incoming_ping');
	//elgg_register_plugin_hook_handler('pingback:object:subtypes', 'object', 'blog_pingback_subtypes');

	// Register for search.
	register_entity_type('object', 'blog');

	//add_widget_type('blog', elgg_echo('blog'), elgg_echo('blog:widget:description'), 'profile, dashboard');

	$action_path = dirname(__FILE__) . '/actions/blog';

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
 * To maintain URL backward compatibility, expects old-style URLs like:
 * 	pg/blog/[username/[read|edit|archive|new/[time_start|guid/[time_end|title]]]]
 *
 * Without a username, show all blogs
 * Without an action (read|edit|archive|new), forward to pg/blog/username/read.
 * Without a guid, show all post for that user.
 * Title is ignored
 *
 * If archive, uses time_start/end
 *
 * @todo There is no way to say "show me archive view for all blog posts" with the
 * current URL scheme because $param[0] is the username instead of an action.
 * Could do something hideous like make '*' mean "all users" (since a username can't be *).
 * Can't change the URL scheme because of URL compatibility.
 *
 * @param array $page
 * @return NULL
 */
function blog_page_handler($page) {
	global $CONFIG;

	// push breadcrumb
	elgg_push_breadcrumb(elgg_echo('blog:blogs'), "pg/blog");

	// see if we're showing all or just a user's
	if (isset($page[0]) && !empty($page[0])) {
		$username = $page[0];

		// forward away if invalid user.
		if (!$user = get_user_by_username($username)) {
			register_error('blog:error:unknown_username');
			forward(REFERER);
		}

		set_page_owner($user->getGUID());
		$crumbs_title = elgg_echo('blog:owned_blogs', array($user->name));
		$crumbs_url = "pg/blog/$username/read";
		elgg_push_breadcrumb($crumbs_title, $crumbs_url);

		$action = isset($page[1]) ? $page[1] : FALSE;
		// yeah these are crap names, but they're used for different things.
		$page2 = isset($page[2]) ? $page[2] : FALSE;
		$page3 = isset($page[3]) ? $page[3] : FALSE;

		switch ($action) {
			case 'read':
				$title = elgg_echo('blog:title:user_blogs', array($user->name));
				$params = blog_get_page_content_read($user->getGUID(), $page2);
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
				forward("pg/blog/$username/read/");
				break;
		}
	} else {
		$title = elgg_echo('blog:title:all_blogs');
		$params = blog_get_page_content_read();
	}

	$sidebar_menu = elgg_view('blog/sidebar_menu', array(
		'page' => isset($page[1]) ? $page[1] : FALSE,
	));

	$params['sidebar'] .= $sidebar_menu;

	$body = elgg_view_layout('main_content', $params);

	echo elgg_view_page($title, $body);
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

	return "pg/blog/{$user->username}/read/{$entity->guid}/$friendly_title";
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
	global $CONFIG;

	if (!($params['owner'] instanceof ElggGroup)) {
		$return_value[] = array(
			'text' => elgg_echo('blog'),
			'href' => "pg/blog/{$params['owner']->username}/read",
		);
	}

	return $return_value;
}

elgg_register_event_handler('init', 'system', 'blog_init');
