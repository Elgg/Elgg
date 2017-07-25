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
	elgg_register_menu_item('site', [
		'name' => 'blog',
		'text' => elgg_echo('blog:blogs'),
		'href' => 'blog/all',
	]);

	// add to the main css
	elgg_extend_view('elgg.css', 'blog/css');

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

	// entity menu
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'blog_entity_menu_setup');

	// archive menu
	elgg_register_plugin_hook_handler('register', 'menu:blog_archive', 'blog_archive_menu_setup');

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
	$entity = elgg_extract('entity', $params);
	if (!elgg_instanceof($entity, 'object', 'blog')) {
		return;
	}
	
	$friendly_title = elgg_get_friendly_title($entity->title);
	return "blog/view/{$entity->guid}/$friendly_title";
}

/**
 * Add a menu item to an ownerblock
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
		if ($entity->blog_enable != 'no') {
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
 * Add particular blog links/info to entity menu
 */
function blog_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return;
	}

	$handler = elgg_extract('handler', $params, false);
	if ($handler !== 'blog') {
		return;
	}

	$entity = elgg_extract('entity', $params);
	if ($entity->status == 'published') {
		return;
	}

	$status_text = elgg_echo("status:{$entity->status}");
	$return[] = ElggMenuItem::factory([
		'name' => 'published_status',
		'text' => "<span>$status_text</span>",
		'href' => false,
		'priority' => 150,
	]);
	return $return;
}

/**
 * Add menu items to the archive menu
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
	
	if (elgg_instanceof($page_owner, 'user')) {
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
 * Register blogs with ECML.
 */
function blog_ecml_views_hook($hook, $entity_type, $return_value, $params) {
	$return_value['object/blog'] = elgg_echo('blog:blogs');

	return $return_value;
}
