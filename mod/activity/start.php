<?php

/**
 * Register menu items for the title menu
 *
 * @param string $hook   Hook
 * @param string $type   Type
 * @param array  $return Current return value
 * @param array  $params Hook parameters
 * @return array
 *
 * @access private
 *
 * @since 3.0
 */
function _elgg_activity_owner_block_menu($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);
	
	if ($entity instanceof \ElggUser) {
		$return[] = \ElggMenuItem::factory([
			'name' => 'activity:owner',
			'text' => elgg_echo('activity:owner'),
			'href' => elgg_generate_url('collections:river:owner', ['username' => $entity->username]),
		]);
	}
	
	return $return;
}

/**
 * Called during system init
 *
 * @return void
 */
function elgg_activity_init() {
	
	elgg_extend_view('css/elgg', 'river/filter.css');
	
	elgg_register_menu_item('site', [
		'name' => 'activity',
		'text' => elgg_echo('activity'),
		'href' => elgg_generate_url('default:river'),
	]);
	
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', '_elgg_activity_owner_block_menu');
}

return function() {
	elgg_register_event_handler('init', 'system', 'elgg_activity_init');
};
