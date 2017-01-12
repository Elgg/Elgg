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
		'href' => elgg_generate_url('blog_all'),
	]);

	// add to the main css
	elgg_extend_view('elgg.css', 'blog/css');

	// override the default url to view a blog object
	elgg_register_plugin_hook_handler('entity:url', 'object', 'blog_set_url');

	// notifications
	elgg_register_notification_event('object', 'blog', array('publish'));
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
	
	return elgg_generate_url('blog_view', [
		'guid' => $entity->guid,
		'title' => elgg_get_friendly_title($entity->title),
	]);
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
					'href' => elgg_generate_url('blog_owner', [
						'username' => $entity->username,
					]),
		]);
	} elseif ($entity instanceof ElggGroup) {
		if ($entity->blog_enable != 'no') {
			$return[] = ElggMenuItem::factory([
						'name' => 'blog',
						'text' => elgg_echo('blog:group'),
						'href' => elgg_generate_url('blog_group', [
							'group_guid' => $entity->guid,
							'subpage' => 'all',
						]),
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
	
	// draft status replaces access
	foreach ($return as $index => $item) {
		if ($item->getName() == 'access') {
			unset($return[$index]);
		}
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
	
	$generate_url = function($lower = null, $upper = null) use ($page_owner) {
		if (elgg_instanceof($page_owner, 'user')) {
			$url_segment = elgg_generate_url('blog_archive', [
				'username' => $page_owner->username,
				'lower' => $lower,
				'upper' => $upper,
			]);
		} else {

			$url_segment = elgg_generate_url('blog_group', [
				'guid' => $page_owner->guid,
				'subpage' => 'archive',
				'lower' => $lower,
				'upper' => $upper,
			]);
		}
	};
	
	$years = [];
	foreach ($dates as $date) {
		$timestamplow = mktime(0, 0, 0, substr($date,4,2) , 1, substr($date, 0, 4));
		$timestamphigh = mktime(0, 0, 0, ((int) substr($date, 4, 2)) + 1, 1, substr($date, 0, 4));
	
		$year = substr($date, 0, 4);
		if (!in_array($year, $years)) {
			$return[] = ElggMenuItem::factory([
				'name' => $year,
				'text' => $year,
				'href' => '#',
			]);
		}
		
		$month = trim(elgg_echo('date:month:' . substr($date, 4, 2), ['']));
		
		$return[] = ElggMenuItem::factory([
			'name' => $date,
			'text' => $month,
			'href' => $generate_url($timestamplow, $timestamphigh),
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
