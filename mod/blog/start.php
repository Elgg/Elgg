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

	elgg_register_library('elgg:blog', __DIR__ . '/lib/blog.php');

	// add a site navigation item
	elgg_register_menu_item('site', [
		'name' => 'blog',
		'text' => elgg_echo('blog:blogs'),
		'href' => elgg_generate_url('collection:object:blog:all'),
	]);

	elgg_extend_view('object/elements/imprint/contents', 'blog/imprint/status');

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
					'href' => elgg_generate_url('collection:object:blog:owner', [
						'username' => $entity->username,
					]),
		]);
	} elseif ($entity instanceof ElggGroup) {
		if ($entity->isToolEnabled('blog')) {
			$return[] = ElggMenuItem::factory([
						'name' => 'blog',
						'text' => elgg_echo('blog:group'),
						'href' => elgg_generate_url('collection:object:blog:group', [
							'guid' => $entity->guid,
							'subpage' => 'all',
						]),
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
	
	$generate_url = function($lower = null, $upper = null) use ($page_owner) {
		if ($page_owner instanceof ElggUser) {
			$url_segment = elgg_generate_url('collection:object:blog:archive', [
				'username' => $page_owner->username,
				'lower' => $lower,
				'upper' => $upper,
			]);
		} else {
			$url_segment = elgg_generate_url('collection:object:blog:group', [
				'guid' => $page_owner->guid,
				'subpage' => 'archive',
				'lower' => $lower,
				'upper' => $upper,
			]);
		}

		return $url_segment;
	};
	
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
