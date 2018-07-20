<?php

/**
 * Register menu items for the title menu
 *
 * @param \Elgg\Hook $hook Hook
 *
 * @return array
 *
 * @access private
 *
 * @since 3.0
 */
function _elgg_activity_owner_block_menu(\Elgg\Hook $hook) {

	$entity = $hook->getEntityParam();
	$return = $hook->getValue();
	
	if ($entity instanceof \ElggUser) {
		$return[] = \ElggMenuItem::factory([
			'name' => 'activity:owner',
			'text' => elgg_echo('activity:owner'),
			'href' => elgg_generate_url('collection:river:owner', ['username' => $entity->username]),
		]);
	}
	
	if ($entity instanceof \ElggGroup && $entity->isToolEnabled('activity')) {
		$return[] = \ElggMenuItem::factory([
			'name' => 'collection:river:group',
			'text' => elgg_echo('collection:river:group'),
			'href' => elgg_generate_url('collection:river:group', ['guid' => $entity->guid]),
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
		'icon' => 'clock-o',
		'text' => elgg_echo('activity'),
		'href' => elgg_generate_url('default:river'),
	]);
	
	elgg()->group_tools->register('activity');
	
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', '_elgg_activity_owner_block_menu');
}

return function() {
	elgg_register_event_handler('init', 'system', 'elgg_activity_init');
};
