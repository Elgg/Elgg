<?php
/**
 * Blogs
 *
 * @package Blog
 */

/**
 * Init blog plugin
 *
 * @return void
 */
function blog_init() {
	elgg()->service('blog.bar')->test('bar');

	elgg_register_library('elgg:blog', __DIR__ . '/lib/blog.php');

	// add a site navigation item
	elgg_register_menu_item('site', [
		'name' => 'blog',
		'text' => elgg_echo('blog:blogs'),
		'href' => 'blog/all',
	]);

	elgg_extend_view('object/elements/imprint/contents', 'blog/imprint/status');

	// routing of urls
	elgg_register_page_handler('blog', 'blog_page_handler');

	// override the default url to view a blog object
	elgg_register_plugin_hook_handler('entity:url', 'object', 'blog_set_url');

	// notifications
	elgg_register_notification_event('object', 'blog', ['publish']);
	elgg_register_plugin_hook_handler('prepare', 'notification:publish:object:blog', 'blog_prepare_notification');

	// add blog link to owner block
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'blog_owner_block_menu');

	// Add group option
	add_group_tool_option('blog', elgg_echo('blog:enableblog'), true);
	elgg_extend_view('groups/tool_latest', 'blog/group_module');

	// archive menu
	elgg_register_plugin_hook_handler('register', 'menu:blog_archive', 'blog_archive_menu_setup');

	// ecml
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'blog_ecml_views_hook');

	// allow to be liked
	elgg_register_plugin_hook_handler('likes:is_likable', 'object:blog', 'Elgg\Values::getTrue');

	// register database seed
	elgg_register_plugin_hook_handler('seeds', 'database', 'blog_register_db_seeds');
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
 * @param array $page URL segments
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
 * @param string $hook   'entity:url'
 * @param string $type   'object'
 * @param string $url    current value
 * @param array  $params supplied params
 *
 * @return string URL of blog
 */
function blog_set_url($hook, $type, $url, $params) {
	$entity = elgg_extract('entity', $params);
	if (!$entity instanceof ElggBlog) {
		return;
	}

	$friendly_title = elgg_get_friendly_title($entity->title);
	return "blog/view/{$entity->guid}/$friendly_title";
}

/**
 * Add a menu item to an ownerblock
 *
 * @param string         $hook   'register'
 * @param string         $type   'menu:owner_block'
 * @param ElggMenuItem[] $return current return value
 * @param array          $params supplied params
 *
 * @return ElggMenuItem[]
 */
function blog_owner_block_menu($hook, $type, $return, $params) {
	$entity = elgg_extract('entity', $params);
	if ($entity instanceof ElggUser) {
		$return[] = ElggMenuItem::factory([
			'name' => 'blog',
			'text' => elgg_echo('blog'),
			'href' => "blog/owner/{$entity->username}",
		]);
	} elseif ($entity instanceof ElggGroup) {
		if ($entity->isToolEnabled('blog')) {
			$return[] = ElggMenuItem::factory([
				'name' => 'blog',
				'text' => elgg_echo('blog:group'),
				'href' => "blog/group/{$entity->guid}/all",
			]);
		}
	}

	return $return;
}

/**
 * Add menu items to the archive menu
 *
 * @param string         $hook   'register'
 * @param string         $type   'menu:blog_archive'
 * @param ElggMenuItem[] $return current return value
 * @param array          $params supplied params
 *
 * @return void|ElggMenuItem[]
 */
function blog_archive_menu_setup($hook, $type, $return, $params) {

	$page_owner = elgg_get_page_owner_entity();
	if (empty($page_owner)) {
		return;
	}

	$dates = get_entity_dates('object', 'blog', $page_owner->getGUID());
	if (!$dates) {
		return;
	}

	$dates = array_reverse($dates);

	if ($page_owner instanceof ElggUser) {
		$url_segment = 'blog/archive/' . $page_owner->username;
	} else {
		$url_segment = 'blog/group/' . $page_owner->getGUID() . '/archive';
	}

	$years = [];
	foreach ($dates as $date) {
		$timestamplow = mktime(0, 0, 0, substr($date, 4, 2), 1, substr($date, 0, 4));
		$timestamphigh = mktime(0, 0, 0, ((int) substr($date, 4, 2)) + 1, 1, substr($date, 0, 4));

		$year = substr($date, 0, 4);
		if (!in_array($year, $years)) {
			$return[] = ElggMenuItem::factory([
				'name' => $year,
				'text' => $year,
				'href' => '#',
				'child_menu' => [
					'display' => 'toggle',
				]
			]);
		}

		$link = $url_segment . '/' . $timestamplow . '/' . $timestamphigh;
		$month = trim(elgg_echo('date:month:' . substr($date, 4, 2), ['']));

		$return[] = ElggMenuItem::factory([
			'name' => $date,
			'text' => $month,
			'href' => $link,
			'parent_name' => $year,
		]);
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

	$notification->subject = elgg_echo('blog:notify:subject', [$entity->title], $language);
	$notification->body = elgg_echo('blog:notify:body', [
		$owner->name,
		$entity->title,
		$entity->getExcerpt(),
		$entity->getURL()
	], $language);
	$notification->summary = elgg_echo('blog:notify:summary', [$entity->title], $language);
	$notification->url = $entity->getURL();

	return $notification;
}

/**
 * Register blogs with ECML
 *
 * @param string $hook         'get_views'
 * @param string $type         'ecml'
 * @param array  $return_value current return value
 * @param array  $params       supplied params
 *
 * @return array
 */
function blog_ecml_views_hook($hook, $type, $return_value, $params) {
	$return_value['object/blog'] = elgg_echo('blog:blogs');

	return $return_value;
}

/**
 * Register database seed
 *
 * @elgg_plugin_hook seeds database
 *
 * @param \Elgg\Hook $hook Hook
 * @return array
 */
function blog_register_db_seeds(\Elgg\Hook $hook) {

	$seeds = $hook->getValue();

	$seeds[] = \Elgg\Blog\Seeder::class;

	return $seeds;
}

return function() {
	elgg_register_event_handler('init', 'system', 'blog_init');
};
