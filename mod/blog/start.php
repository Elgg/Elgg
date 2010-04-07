<?php
/**
 * Blogs
 *
 * @package Blog
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
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

	add_menu(elgg_echo('blog:blogs'), "{$CONFIG->wwwroot}pg/blog/", array());

	// run the setup upon activations or to upgrade old installations.
	run_function_once('blog_runonce', '1269370108');

	elgg_extend_view('css', 'blog/css');

	register_elgg_event_handler('pagesetup', 'system', 'blog_page_setup');
	register_page_handler('blog', 'blog_page_handler');
	register_entity_url_handler('blog_url_handler', 'object', 'blog');

	// notifications
	register_notification_object('object', 'blog', elgg_echo('blog:newpost'));
	register_plugin_hook('notify:entity:message', 'object', 'blog_notify_message');

	// pingbacks
	//register_elgg_event_handler('create', 'object', 'blog_incoming_ping');
	//register_plugin_hook('pingback:object:subtypes', 'object', 'blog_pingback_subtypes');

	// Register for search.
	register_entity_type('object', 'blog');

	add_group_tool_option('blog', elgg_echo('blog:enableblog'), true);

	//add_widget_type('blog', elgg_echo('blog'), elgg_echo('blog:widget:description'), 'profile, dashboard');

	$action_path = dirname(__FILE__) . '/actions/blog';

	register_action('blog/save', FALSE, "$action_path/save.php");
	register_action('blog/auto_save_revision', FALSE, "$action_path/auto_save_revision.php");
	register_action('blog/delete', FALSE, "$action_path/delete.php");
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

	// see if we're showing all or just a user's
	if (isset($page[0]) && !empty($page[0])) {
		$username = $page[0];
		
		// push breadcrumb
		elgg_push_breadcrumb(elgg_echo('blog:blogs'), "{$CONFIG->site->url}pg/blog");

		// forward away if invalid user.
		if (!$user = get_user_by_username($username)) {
			register_error('blog:error:unknown_username');
			forward($_SERVER['HTTP_REFERER']);
		}

		set_page_owner($user->getGUID());
		$crumbs_title = sprintf(elgg_echo('blog:owned_blogs'), $user->name);
		$crumbs_url = "{$CONFIG->site->url}pg/blog/$username/read";
		elgg_push_breadcrumb($crumbs_title, $crumbs_url);

		$action = isset($page[1]) ? $page[1] : FALSE;
		// yeah these are crap names, but they're used for different things.
		$page2 = isset($page[2]) ? $page[2] : FALSE;
		$page3 = isset($page[3]) ? $page[3] : FALSE;

		switch ($action) {
			case 'read':
				$title = sprintf(elgg_echo('blog:title:user_blogs'), $user->name);
				$content_info = blog_get_page_content_read($user->getGUID(), $page2);
				break;

			case 'new':
			case 'edit':
				$title = elgg_echo('blog:edit');
				$content_info = blog_get_page_content_edit($page2, $page3);
				break;

			case 'archive':
				$title = elgg_echo('blog:archives');
				$content_info = blog_get_page_content_archive($user->getGUID(), $page2, $page3);
				break;

			case 'friends':
				$title = elgg_echo('blog:title:friends');
				$content_info = blog_get_page_content_friends($user->getGUID());
				break;

			default:
				forward("pg/blog/$username/read/");
				break;
		}
	} else {
		$title = elgg_echo('blog:title:all_blogs');
		$content_info = blog_get_page_content_read();
	}

	$sidebar .= elgg_view('blog/sidebar_menu');
	if (isset($content_info['sidebar'])) {
		$sidebar .= $content_info['sidebar'];
	}
	$content = elgg_view('navigation/breadcrumbs') . $content_info['content'];

	$body = elgg_view_layout('one_column_with_sidebar', $content, $sidebar);

	page_draw($title, $body);
}

/**
 * Format and return the correct URL for blogs.
 *
 * @param ElggObject $entity
 * @return string URL of blog.
 */
function blog_url_handler($entity) {
	global $CONFIG;

	if (!$user = get_entity($entity->owner_guid)) {
		// default to a standard view if no owner.
		return FALSE;
	}

	$friendly_title = friendly_title($entity->title);

	$url = "{$CONFIG->site->url}pg/blog/{$user->username}/read/{$entity->getGUID()}/$friendly_title";
	return $url;
}

/**
 * Add menu items for groups
 */
function blog_page_setup() {
	global $CONFIG;
	$page_owner = page_owner_entity();

	if ($page_owner instanceof ElggGroup && get_context() == 'groups') {
		if($page_owner->blog_enable != "no") {
			$url = "{$CONFIG->wwwroot}pg/blog/{$page_owner->username}/items";
			add_submenu_item(elgg_echo('blog:groups:group_blogs'), $url);
		}
	}
}

register_elgg_event_handler('init', 'system', 'blog_init');
