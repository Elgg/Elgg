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
 */

elgg_register_event_handler('init', 'system', 'blog_init');

/**
 * Init blog plugin.
 */
function blog_init() {

	elgg_register_library('elgg:blog', __DIR__ . '/lib/blog.php');

	// add a site navigation item
	$item = new ElggMenuItem('blog', elgg_echo('blog:blogs'), 'blog/all');
	elgg_register_menu_item('site', $item);

	elgg_register_event_handler('upgrade', 'upgrade', 'blog_run_upgrades');

	// add to the main css
	elgg_extend_view('elgg.css', 'blog/css');

	// routing of urls
	elgg_register_page_handler('blog', 'blog_page_handler');

	// override the default url to view a blog object
	elgg_register_plugin_hook_handler('entity:url', 'object', 'blog_set_url');

	// notifications
	elgg_register_notification_event('object', 'blog', array('publish'));
	elgg_register_plugin_hook_handler('prepare', 'notification:publish:object:blog', 'blog_prepare_notification');

	// add blog link to
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'blog_owner_block_menu');

	// pingbacks
	//elgg_register_event_handler('create', 'object', 'blog_incoming_ping');
	//elgg_register_plugin_hook_handler('pingback:object:subtypes', 'object', 'blog_pingback_subtypes');

	// Register for search.
	elgg_register_entity_type('object', 'blog');

	// Add group option
	add_group_tool_option('blog', elgg_echo('blog:enableblog'), true);
	elgg_extend_view('groups/tool_latest', 'blog/group_module');

	// add a blog widget
	elgg_register_widget_type('blog', elgg_echo('blog'), elgg_echo('blog:widget:description'));

	// register actions
	$action_path = __DIR__ . '/actions/blog';
	elgg_register_action('blog/save', "$action_path/save.php");
	elgg_register_action('blog/auto_save_revision', "$action_path/auto_save_revision.php");
	elgg_register_action('blog/delete', "$action_path/delete.php");

	// entity menu
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'blog_entity_menu_setup');

	// ecml
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'blog_ecml_views_hook');

	// allow to be liked
	elgg_register_plugin_hook_handler('likes:is_likable', 'object:blog', 'Elgg\Values::getTrue');
}

/**
 * Dispatches blog pages.
 * URLs take the form of
 *  All blogs:       blog/all
 *  User's blogs:    blog/owner/<username>
 *  Friends' blog:   blog/friends/<username>
 *  User's archives: blog/archives/<username>/<time_start>/<time_stop>
 *  Blog post:       blog/view/<guid>/<title>
 *  New post:        blog/add/<guid>
 *  Edit post:       blog/edit/<guid>/<revision>
 *  Preview post:    blog/preview/<guid>
 *  Group blog:      blog/group/<guid>/all
 *
 * Title is ignored
 *
 * @todo no archives for all blogs or friends
 *
 * @param array $page
 * @return bool
 */
function blog_page_handler($page) {

	elgg_load_library('elgg:blog');

	// push all blogs breadcrumb
	elgg_push_breadcrumb(elgg_echo('blog:blogs'), 'blog/all');

	$page_type = elgg_extract(0, $page, 'all');
	$resource_vars = [
		'page_type' => $page_type,
	];

	switch ($page_type) {
		case 'owner':
			$resource_vars['username'] = elgg_extract(1, $page);
			
			echo elgg_view_resource('blog/owner', $resource_vars);
			break;
		case 'friends':
			$resource_vars['username'] = elgg_extract(1, $page);
			
			echo elgg_view_resource('blog/friends', $resource_vars);
			break;
		case 'archive':
			$resource_vars['username'] = elgg_extract(1, $page);
			$resource_vars['lower'] = elgg_extract(2, $page);
			$resource_vars['upper'] = elgg_extract(3, $page);
			
			echo elgg_view_resource('blog/archive', $resource_vars);
			break;
		case 'view':
			$resource_vars['guid'] = elgg_extract(1, $page);
			
			echo elgg_view_resource('blog/view', $resource_vars);
			break;
		case 'add':
			$resource_vars['guid'] = elgg_extract(1, $page);
			
			echo elgg_view_resource('blog/add', $resource_vars);
			break;
		case 'edit':
			$resource_vars['guid'] = elgg_extract(1, $page);
			$resource_vars['revision'] = elgg_extract(2, $page);
			
			echo elgg_view_resource('blog/edit', $resource_vars);
			break;
		case 'group':
			$resource_vars['group_guid'] = elgg_extract(1, $page);
			$resource_vars['subpage'] = elgg_extract(2, $page);
			$resource_vars['lower'] = elgg_extract(3, $page);
			$resource_vars['upper'] = elgg_extract(4, $page);
			
			echo elgg_view_resource('blog/group', $resource_vars);
			break;
		case 'all':
			echo elgg_view_resource('blog/all', $resource_vars);
			break;
		default:
			return false;
	}

	return true;
}

/**
 * Format and return the URL for blogs.
 *
 * @param string $hook
 * @param string $type
 * @param string $url
 * @param array  $params
 * @return string URL of blog.
 */
function blog_set_url($hook, $type, $url, $params) {
	$entity = $params['entity'];
	if (elgg_instanceof($entity, 'object', 'blog')) {
		$friendly_title = elgg_get_friendly_title($entity->title);
		return "blog/view/{$entity->guid}/$friendly_title";
	}
}

/**
 * Add a menu item to an ownerblock
 */
function blog_owner_block_menu($hook, $type, $return, $params) {
	$entity = elgg_extract('entity', $params);
	if ($entity instanceof ElggUser) {
		$url = "blog/owner/{$entity->username}";
		$return[] = new ElggMenuItem('blog', elgg_echo('blog'), $url);

	} elseif ($entity instanceof ElggGroup) {
		if ($entity->blog_enable != "no") {
			$url = "blog/group/{$entity->guid}/all";
			$return[] = new ElggMenuItem('blog', elgg_echo('blog:group'), $url);
		}
	}

	return $return;
}

/**
 * Add particular blog links/info to entity menu
 */
function blog_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];
	$handler = elgg_extract('handler', $params, false);
	if ($handler != 'blog') {
		return $return;
	}

	if ($entity->status != 'published') {
		// draft status replaces access
		foreach ($return as $index => $item) {
			if ($item->getName() == 'access') {
				unset($return[$index]);
			}
		}

		$status_text = elgg_echo("status:{$entity->status}");
		$options = array(
			'name' => 'published_status',
			'text' => "<span>$status_text</span>",
			'href' => false,
			'priority' => 150,
		);
		$return[] = ElggMenuItem::factory($options);
	}

	return $return;
}

/**
 * Prepare a notification message about a published blog
 *
 * @param string                          $hook         Hook name
 * @param string                          $type         Hook type
 * @param Elgg\Notifications\Notification $notification The notification to prepare
 * @param array                           $params       Hook parameters
 * @return Elgg\Notifications\Notification
 */
function blog_prepare_notification($hook, $type, $notification, $params) {
	$entity = $params['event']->getObject();
	$owner = $params['event']->getActor();
	$recipient = $params['recipient'];
	$language = $params['language'];
	$method = $params['method'];

	$notification->subject = elgg_echo('blog:notify:subject', array($entity->title), $language);
	$notification->body = elgg_echo('blog:notify:body', array(
		$owner->name,
		$entity->title,
		$entity->getExcerpt(),
		$entity->getURL()
	), $language);
	$notification->summary = elgg_echo('blog:notify:summary', array($entity->title), $language);

	return $notification;
}

/**
 * Register blogs with ECML.
 */
function blog_ecml_views_hook($hook, $entity_type, $return_value, $params) {
	$return_value['object/blog'] = elgg_echo('blog:blogs');

	return $return_value;
}

/**
 * Upgrade from 1.7 to 1.8.
 */
function blog_run_upgrades($event, $type, $details) {
	$blog_upgrade_version = elgg_get_plugin_setting('upgrade_version', 'blogs');

	if (!$blog_upgrade_version) {
		 // When upgrading, check if the ElggBlog class has been registered as this
		 // was added in Elgg 1.8
		if (!update_subtype('object', 'blog', 'ElggBlog')) {
			add_subtype('object', 'blog', 'ElggBlog');
		}

		elgg_set_plugin_setting('upgrade_version', 1, 'blogs');
	}
}
