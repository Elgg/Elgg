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

	// add a site navigation item
	elgg_register_menu_item('site', [
		'name' => 'blog',
		'icon' => 'pencil-square-o',
		'text' => elgg_echo('collection:object:blog'),
		'href' => elgg_generate_url('collection:object:blog:all'),
	]);

	// notifications
	elgg_register_notification_event('object', 'blog', ['publish']);
	elgg_register_plugin_hook_handler('prepare', 'notification:publish:object:blog', 'blog_prepare_notification');

	// add blog link to owner block
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'blog_owner_block_menu');

	// Add group option
	elgg()->group_tools->register('blog');

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
					'text' => elgg_echo('collection:object:blog'),
					'href' => elgg_generate_url('collection:object:blog:owner', [
						'username' => $entity->username,
					]),
		]);
	} elseif ($entity instanceof ElggGroup) {
		if ($entity->isToolEnabled('blog')) {
			$return[] = ElggMenuItem::factory([
						'name' => 'blog',
						'text' => elgg_echo('collection:object:blog:group'),
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

	$page_owner = elgg_extract('entity', $params, elgg_get_page_owner_entity());
	$page = elgg_extract('page', $params, 'all');
	if (!in_array($page, ['all', 'owner', 'friends', 'group'])) {
		// only generate archive menu for supported pages
		return;
	}
	
	$options = [
		'type' => 'object',
		'subtype' => 'blog',
	];
	if ($page_owner instanceof ElggUser) {
		if ($page === 'friends') {
			$options['relationship'] = 'friend';
			$options['relationship_guid'] = (int) $page_owner->guid;
			$options['relationship_join_on'] = 'owner_guid';
		} else {
			$options['owner_guid'] = $page_owner->guid;
		}
	} elseif ($page_owner instanceof ElggEntity) {
		$options['container_guid'] = $page_owner->guid;
	}
	
	$dates = elgg_get_entity_dates($options);
	if (!$dates) {
		return;
	}

	$dates = array_reverse($dates);
	
	$generate_url = function($lower = null, $upper = null) use ($page_owner, $page) {
		if ($page_owner instanceof ElggUser) {
			if ($page === 'friends') {
				$url_segment = elgg_generate_url('collection:object:blog:friends', [
					'username' => $page_owner->username,
					'lower' => $lower,
					'upper' => $upper,
				]);
			} else {
				$url_segment = elgg_generate_url('collection:object:blog:owner', [
					'username' => $page_owner->username,
					'lower' => $lower,
					'upper' => $upper,
				]);
			}
		} else if ($page_owner instanceof ElggGroup) {
			$url_segment = elgg_generate_url('collection:object:blog:group', [
				'guid' => $page_owner->guid,
				'subpage' => 'archive',
				'lower' => $lower,
				'upper' => $upper,
			]);
		} else {
			$url_segment = elgg_generate_url('collection:object:blog:all', [
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
				],
				'priority' => -(int) "{$year}00", // make negative to be sure 2019 is before 2018
			]);
		}

		$month = trim(elgg_echo('date:month:' . substr($date, 4, 2), ['']));

		$return[] = ElggMenuItem::factory([
			'name' => $date,
			'text' => $month,
			'href' => $generate_url($timestamplow, $timestamphigh),
			'parent_name' => $year,
			'priority' => -(int) $date, // make negative to be sure March 2019 is before February 2019
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

	$notification->subject = elgg_echo('blog:notify:subject', [$entity->getDisplayName()], $language);
	$notification->body = elgg_echo('blog:notify:body', [
		$owner->getDisplayName(),
		$entity->getDisplayName(),
		$entity->getExcerpt(),
		$entity->getURL()
	], $language);
	$notification->summary = elgg_echo('blog:notify:summary', [$entity->getDisplayName()], $language);
	$notification->url = $entity->getURL();

	return $notification;
}


/**
 * Pull together blog variables for the save form
 *
 * @param ElggBlog       $post     blog post being edited
 * @param ElggAnnotation $revision a revision from which to edit
 * @return array
 */
function blog_prepare_form_vars($post = null, $revision = null) {

	// input names => defaults
	$values = [
		'title' => null,
		'description' => null,
		'status' => 'published',
		'access_id' => ACCESS_DEFAULT,
		'comments_on' => 'On',
		'excerpt' => null,
		'tags' => null,
		'container_guid' => null,
		'guid' => null,
		'entity' => $post,
		'draft_warning' => '',
	];

	if ($post) {
		foreach (array_keys($values) as $field) {
			if (isset($post->$field)) {
				$values[$field] = $post->$field;
			}
		}

		if ($post->status == 'draft') {
			$values['access_id'] = $post->future_access;
		}
	}

	if (elgg_is_sticky_form('blog')) {
		$sticky_values = elgg_get_sticky_values('blog');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	$params = ['entity' => $post];
	$values = elgg_trigger_plugin_hook('form:values', 'blog', $params, $values);

	elgg_clear_sticky_form('blog');

	if (!$post) {
		return $values;
	}

	// load the revision annotation if requested
	if ($revision instanceof ElggAnnotation && $revision->entity_guid == $post->getGUID()) {
		$values['revision'] = $revision;
		$values['description'] = $revision->value;
	}

	// display a notice if there's an autosaved annotation
	// and we're not editing it.
	$auto_save_annotations = $post->getAnnotations([
		'annotation_name' => 'blog_auto_save',
		'limit' => 1,
	]);
	if ($auto_save_annotations) {
		$auto_save = $auto_save_annotations[0];
	} else {
		$auto_save = false;
	}
	/* @var ElggAnnotation|false $auto_save */

	if ($auto_save && $revision && $auto_save->id != $revision->id) {
		$values['draft_warning'] = elgg_echo('blog:messages:warning:draft');
	}

	return $values;
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
	$return_value['object/blog'] = elgg_echo('item:object:blog');

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
