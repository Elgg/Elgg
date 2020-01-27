<?php
/**
 * Elgg Bookmarks plugin
 */

/**
 * Bookmark init
 *
 * @return void
 */
function bookmarks_init() {

	// menus
	elgg_register_menu_item('site', [
		'name' => 'bookmarks',
		'icon' => 'bookmark-o',
		'text' => elgg_echo('collection:object:bookmarks'),
		'href' => elgg_generate_url('default:object:bookmarks'),
	]);
	
	elgg_register_plugin_hook_handler('register', 'menu:page', 'bookmarks_page_menu');
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'bookmarks_owner_block_menu');
	elgg_register_plugin_hook_handler('register', 'menu:footer', 'bookmarks_footer_menu');

	elgg_extend_view('elgg.js', 'bookmarks.js');

	// Register for notifications
	elgg_register_notification_event('object', 'bookmarks', ['create']);
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:bookmarks', 'bookmarks_prepare_notification');

	// Register bookmarks view for ecml parsing
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'bookmarks_ecml_views_hook');

	// Groups
	elgg()->group_tools->register('bookmarks');

	// allow to be liked
	elgg_register_plugin_hook_handler('likes:is_likable', 'object:bookmarks', 'Elgg\Values::getTrue');

	// register database seed
	elgg_register_plugin_hook_handler('seeds', 'database', 'bookmarks_register_db_seeds');
}

/**
 * Add a menu item to an ownerblock
 *
 * @param \Elgg\Hook $hook 'register', 'menu:owner_block'
 *
 * @return ElggMenuItem[]
 */
function bookmarks_owner_block_menu(\Elgg\Hook $hook) {

	$entity = $hook->getEntityParam();
	$return = $hook->getValue();
	
	if ($entity instanceof ElggUser) {
		$url = elgg_generate_url('collection:object:bookmarks:owner', ['username' => $entity->username]);
		$item = new ElggMenuItem('bookmarks', elgg_echo('collection:object:bookmarks'), $url);
		$return[] = $item;
	} elseif ($entity instanceof ElggGroup) {
		if ($entity->isToolEnabled('bookmarks')) {
			$url = elgg_generate_url('collection:object:bookmarks:group', ['guid' => $entity->guid]);
			$item = new ElggMenuItem('bookmarks', elgg_echo('collection:object:bookmarks:group'), $url);
			$return[] = $item;
		}
	}
	
	return $return;
}

/**
 * Prepare a notification message about a new bookmark
 *
 * @param \Elgg\Hook $hook 'prepare', 'notification:create:object:bookmarks'
 *
 * @return Elgg\Notifications\Notification
 */
function bookmarks_prepare_notification(\Elgg\Hook $hook) {
	$entity = $hook->getParam('event')->getObject();
	$owner = $hook->getParam('event')->getActor();
	$language = $hook->getParam('language');

	$descr = $entity->description;
	$title = $entity->getDisplayName();

	$notification = $hook->getValue();
	
	$notification->subject = elgg_echo('bookmarks:notify:subject', [$title], $language);
	$notification->body = elgg_echo('bookmarks:notify:body', [
		$owner->getDisplayName(),
		$title,
		$entity->address,
		$descr,
		$entity->getURL()
	], $language);
	$notification->summary = elgg_echo('bookmarks:notify:summary', [$title], $language);
	$notification->url = $entity->getURL();
	return $notification;
}

/**
 * Add a page menu menu
 *
 * @param \Elgg\Hook $hook 'register', 'menu:page'
 *
 * @return void|ElggMenuItem[]
 */
function bookmarks_page_menu(\Elgg\Hook $hook) {
	if (!elgg_is_logged_in()) {
		return;
	}
	// only show bookmarklet in bookmark pages
	if (!elgg_in_context('bookmarks')) {
		return;
	}
	
	$page_owner = elgg_get_page_owner_entity();
	if (!$page_owner) {
		$page_owner = elgg_get_logged_in_user_entity();
	}
	
	if ($page_owner instanceof ElggGroup) {
		$title = elgg_echo('bookmarks:bookmarklet:group');
	} else {
		$title = elgg_echo('bookmarks:bookmarklet');
	}
	
	$return = $hook->getValue();
	
	$return[] = ElggMenuItem::factory([
		'name' => 'bookmarklet',
		'text' => $title,
		'href' => elgg_generate_url('bookmarklet:object:bookmarks', ['guid' => $page_owner->getGUID()]),
	]);

	return $return;
}

/**
 * Adds a add bookmark link to the footer menu
 *
 * @param \Elgg\Hook $hook 'register', 'menu:footer'
 *
 * @return \Elgg\Menu\MenuItems
 */
function bookmarks_footer_menu(\Elgg\Hook $hook) {

	if (!elgg_is_logged_in()) {
		return;
	}
	
	$result = $hook->getValue();
	
	$result[] = \ElggMenuitem::factory([
		'name' => 'bookmark',
		'text' => elgg_echo('bookmarks:this'),
		'icon' => 'push-pin-alt',
		'href' => elgg_generate_url('add:object:bookmarks', [
			'guid' => elgg_get_logged_in_user_guid(),
			'address' => current_page_url(),
		]),
		'title' => elgg_echo('bookmarks:this'),
		'rel' => 'nofollow',
	]);

	return $result;
}

/**
 * Return bookmarks views to parse for ecml
 *
 * @param \Elgg\Hook $hook 'get_views', 'ecml'
 *
 * @return array
 */
function bookmarks_ecml_views_hook(\Elgg\Hook $hook) {
	$return = $hook->getValue();
	$return['object/bookmarks'] = elgg_echo('item:object:bookmarks');
	return $return;
}

/**
 * Prepare the add/edit form variables
 *
 * @param ElggObject $bookmark A bookmark object.
 * @return array
 */
function bookmarks_prepare_form_vars($bookmark = null) {
	// input names => defaults
	$values = [
		'title' => get_input('title', ''), // bookmarklet support
		'address' => get_input('address', ''),
		'description' => '',
		'access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $bookmark,
	];

	if ($bookmark) {
		foreach (array_keys($values) as $field) {
			if (isset($bookmark->$field)) {
				$values[$field] = $bookmark->$field;
			}
		}
	}

	if (elgg_is_sticky_form('bookmarks')) {
		$sticky_values = elgg_get_sticky_values('bookmarks');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('bookmarks');

	return $values;
}

/**
 * Register database seed
 *
 * @param \Elgg\Hook $hook 'seeds', 'database'
 *
 * @return array
 */
function bookmarks_register_db_seeds(\Elgg\Hook $hook) {

	$seeds = $hook->getValue();

	$seeds[] = \Elgg\Bookmarks\Seeder::class;

	return $seeds;
}


return function() {
	elgg_register_event_handler('init', 'system', 'bookmarks_init');
};
