<?php
/**
 * Members plugin initialization
 */

/**
 * Initialize page handler and site menu item
 *
 * @return void
 */
function members_init() {
	elgg_register_page_handler('members', 'members_page_handler');

	elgg_register_menu_item('site', [
		'name' => 'members',
		'text' => elgg_echo('members'),
		'href' => 'members',
	]);

	elgg_register_plugin_hook_handler('register', 'menu:filter:members', 'members_register_filter_menu');
}

/**
 * Registers members filter menu items
 *
 * @elgg_plugin_hook 'register', 'menu:filter:members'
 *
 * @param \Elgg\Hook $hook hook
 *
 * @return \ElggMenuItem[]
 */
function members_register_filter_menu(\Elgg\Hook $hook) {
	$result = (array) $hook->getValue();
	
	$result['newest'] = \ElggMenuItem::factory([
		'name' => 'newest',
		'text' => elgg_echo('sort:newest'),
		'href' => 'members/newest',
		'selected' => $hook->getParam('filter_value') == 'newest',
	]);
	$result['alpha'] =\ElggMenuItem::factory([
		'name' => 'alpha',
		'text' => elgg_echo('sort:alpha'),
		'href' => 'members/alpha',
	]);
	$result['popular'] = \ElggMenuItem::factory([
		'name' => 'popular',
		'text' => elgg_echo('sort:popular'),
		'href' => 'members/popular',
	]);
	$result['online'] = \ElggMenuItem::factory([
		'name' => 'online',
		'text' => elgg_echo('members:label:online'),
		'href' => 'members/online',
	]);
	
	return $result;
}

/**
 * Members page handler
 *
 * @param array $page url segments
 * @return void|true
 */
function members_page_handler($page) {
	if (empty($page[0])) {
		$page[0] = 'newest';
	}
	
	$resource = "members/{$page[0]}";
	if (elgg_view_exists("resources/{$resource}")) {
		echo elgg_view_resource($resource);
		return true;
	}
}

return function() {
	elgg_register_event_handler('init', 'system', 'members_init');
};
