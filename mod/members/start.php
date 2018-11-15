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
	
	elgg_register_menu_item('site', [
		'name' => 'members',
		'icon' => 'address-book-o',
		'text' => elgg_echo('members'),
		'href' => elgg_generate_url('collection:user:user'),
	]);

	if (elgg_is_admin_logged_in()) {
		elgg_register_menu_item('title', [
			'name' => 'add_user',
			'icon' => 'user-plus',
			'text' => elgg_echo('admin:users:add'),
			'href' => 'admin/users/add',
			'context' => 'members',
			'link_class' => 'elgg-button elgg-button-action',
		]);
	}
	
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
	$result = $hook->getValue();
	$filter_value = $hook->getParam('filter_value');
	
	$result['newest'] = \ElggMenuItem::factory([
		'name' => 'newest',
		'text' => elgg_echo('sort:newest'),
		'href' => elgg_generate_url('collection:user:user:newest'),
		'selected' => $filter_value === 'newest',
	]);
	$result['alpha'] =\ElggMenuItem::factory([
		'name' => 'alpha',
		'text' => elgg_echo('sort:alpha'),
		'href' => elgg_generate_url('collection:user:user:alpha'),
		'selected' => $filter_value === 'alpha',
	]);
	$result['popular'] = \ElggMenuItem::factory([
		'name' => 'popular',
		'text' => elgg_echo('sort:popular'),
		'href' => elgg_generate_url('collection:user:user:popular'),
		'selected' => $filter_value === 'popular',
	]);
	$result['online'] = \ElggMenuItem::factory([
		'name' => 'online',
		'text' => elgg_echo('members:label:online'),
		'href' => elgg_generate_url('collection:user:user:online'),
		'selected' => $filter_value === 'online',
	]);
	
	return $result;
}

return function() {
	elgg_register_event_handler('init', 'system', 'members_init');
};
