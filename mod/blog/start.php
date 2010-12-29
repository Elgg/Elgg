<?php
/**
 * Blogs
 *
 * @package Blog
 *
 * @todo
 * - Either drop support for "publish date" or duplicate more entity getter
 * functions to work with a non-standard time_created.
 * - Pingbacks
 * - Notifications
 * - River entry for posts saved as drafts and later published
 * - Group menu
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

	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'blog_owner_block_menu');

	// pingbacks
	//elgg_register_event_handler('create', 'object', 'blog_incoming_ping');
	//elgg_register_plugin_hook_handler('pingback:object:subtypes', 'object', 'blog_pingback_subtypes');

	// Register for search.
	register_entity_type('object', 'blog');

	// Add group option
	add_group_tool_option('blog', elgg_echo('blog:enableblog'), true);
	elgg_extend_view('groups/tool_latest', 'blog/group_module');

	elgg_register_widget_type('blog', elgg_echo('blog'), elgg_echo('blog:widget:description'), 'profile');

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
 *  All blogs:       pg/blog/all
 *  User's blogs:    pg/blog/owner/<username>
 *  Friends' blog:   pg/blog/friends/<username>
 *  User's archives: pg/blog/archives/<username>/<time_start>/<time_stop>
 *  Blog post:       pg/blog/read/<guid>/<title>
 *  New post:        pg/blog/new/<guid>
 *  Edit post:       pg/blog/edit/<guid>/<revision>
 *  Preview post:    pg/blog/preview/<guid>
 *  Group blog:      pg/blog/group/<guid>/owner
 *
 * Title is ignored
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

	// push all blogs breadcrumb
	elgg_push_breadcrumb(elgg_echo('blog:blogs'), "pg/blog/all/");

	if (!isset($page[0])) {
		$page[0] = 'all';
	}
	
	$page_type = $page[0];
	switch ($page_type) {
		case 'owner':
			$user = get_user_by_username($page[1]);
			$params = blog_get_page_content_list($user->guid);
			break;
		case 'friends':
			$user = get_user_by_username($page[1]);
			$params = blog_get_page_content_friends($user->guid);
			break;
		case 'archive':
			$user = get_user_by_username($page[1]);
			$params = blog_get_page_content_archive($user->guid, $page[2], $page[3]);
			break;
		case 'read':
			$params = blog_get_page_content_read($page[1]);
			break;
		case 'new':
			gatekeeper();
			$params = blog_get_page_content_edit($page_type, $page[1]);
			break;
		case 'edit':
			gatekeeper();
			$params = blog_get_page_content_edit($page_type, $page[1], $page[2]);
			break;
		case 'group':
			$params = blog_get_page_content_list($page[1]);
			break;
		case 'all':
		default:
			$title = elgg_echo('blog:title:all_blogs');
			$params = blog_get_page_content_list();
			break;
	}
	
	$params['sidebar'] .= elgg_view('blog/sidebar', array('page' => $page_type));

	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($params['title'], $body);
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
 * Format and return the URL for blogs.
 *
 * @param ElggObject $entity Blog object
 * @return string URL of blog.
 */
function blog_url_handler($entity) {
	if (!$entity->getOwnerEntity()) {
		// default to a standard view if no owner.
		return FALSE;
	}

	$friendly_title = elgg_get_friendly_title($entity->title);

	return "pg/blog/read/{$entity->guid}/$friendly_title";
}

/**
 * Add a menu item to an ownerblock
 */
function blog_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "pg/blog/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('blog', elgg_echo('blog'), $url);
		$return[] = $item;
	} else {
		if ($params['entity']->blog_enable != "no") {
			$url = "pg/blog/group/{$params['entity']->guid}/owner";
			$item = new ElggMenuItem('blog', elgg_echo('blog:group'), $url);
			$return[] = $item;
		}
	}

	return $return;
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
