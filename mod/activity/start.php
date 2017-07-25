<?php

/**
 * Page handler for activity
 *
 * @param array $segments URL segments
 * @return \Elgg\Http\ResponseBuilder
 * @access private
 */
function elgg_activity_page_handler($segments) {
	elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());

	// make a URL segment available in page handler script
	$page_type = elgg_extract(0, $segments, 'all');
	$page_type = preg_replace('[\W]', '', $page_type);

	if ($page_type == 'owner') {
		elgg_gatekeeper();
		$page_username = elgg_extract(1, $segments, '');
		if ($page_username == elgg_get_logged_in_user_entity()->username) {
			$page_type = 'mine';
		} else {
			$vars['subject_username'] = $page_username;
		}
	}

	$vars['page_type'] = $page_type;

	return elgg_ok_response(elgg_view_resource("river", $vars));
}

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
			'href' => "activity/owner/{$entity->username}",
		]);
	}
	
	return $return;
}

function elgg_activity_init() {
	elgg_register_page_handler('activity', 'elgg_activity_page_handler');

	elgg_register_menu_item('site', [
		'name' => 'activity',
		'text' => elgg_echo('activity'),
		'href' => 'activity',
	]);
	
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', '_elgg_activity_owner_block_menu');
}

elgg_register_event_handler('init', 'system', 'elgg_activity_init');
