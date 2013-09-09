<?php
/**
 * Members plugin intialization
 *
 *
 * Adding a list type:
 *
 * Handle the hook (members:list, your_type) and return the list (e.g. members_list_popular()), and
 * use the hook (members:config, tabs) to add your nav item (e.g. members_nav_popular()). Add a language
 * key for the page title: "members:title:your_type".
 *
 * Removing a list type tab:
 *
 * Handle the plugin hook (members:config, tabs) and unset the appropriate type key from the array.
 *
 * Reordering tabs:
 *
 * Handle the plugin hook (members:config, tabs) and re-order the appearance of the keys in the array.
 */

elgg_register_event_handler('init', 'system', 'members_init');

/**
 * Initialize page handler and site menu item
 */
function members_init() {
	elgg_register_page_handler('members', 'members_page_handler');

	$item = new ElggMenuItem('members', elgg_echo('members'), 'members');
	elgg_register_menu_item('site', $item);

	$list_types = array('newest', 'popular', 'online');

	foreach ($list_types as $type) {
		elgg_register_plugin_hook_handler('members:list', $type, "members_list_$type");
		elgg_register_plugin_hook_handler('members:config', 'tabs', "members_nav_$type");
	}
}

// handle hook (members:list, popular)
function members_list_popular($hook, $type, $returnvalue, $params) {
	if ($returnvalue) {
		return;
	}

	$options = $params['options'];
	$options['relationship'] = 'friend';
	$options['inverse_relationship'] = false;
	return elgg_list_entities_from_relationship_count($options);
}

// handle hook (members:list, newest)
function members_list_newest($hook, $type, $returnvalue, $params) {
	if ($returnvalue) {
		return;
	}
	return elgg_list_entities($params['options']);
}

// handle hook (members:list, online)
function members_list_online($hook, $type, $returnvalue, $params) {
	if ($returnvalue) {
		return;
	}
	return get_online_users();
}

// handle hook (members:config, tabs) add tab for popular
function members_nav_popular($hook, $type, $returnvalue, $params) {
	$returnvalue['popular'] = array(
		'title' => elgg_echo('sort:popular'),
		'url' => "members/popular",
	);
	return $returnvalue;
}

// handle hook (members:config, tabs) add tab for newest
function members_nav_newest($hook, $type, $returnvalue, $params) {
	$returnvalue['newest'] = array(
		'title' => elgg_echo('sort:newest'),
		'url' => "members",
	);
	return $returnvalue;
}

// handle hook (members:config, tabs) add tab for online
function members_nav_online($hook, $type, $returnvalue, $params) {
	$returnvalue['online'] = array(
		'title' => elgg_echo('members:label:online'),
		'url' => "members/online",
	);
	return $returnvalue;
}


/**
 * Members page handler
 *
 * @param array $page url segments
 * @return bool
 */
function members_page_handler($page) {
	$base = elgg_get_plugins_path() . 'members/pages/members';

	if (!isset($page[0])) {
		$page[0] = 'newest';
	}

	$vars = array();
	$vars['page'] = $page[0];

	if ($page[0] == 'search') {
		require_once "$base/search.php";
	} else {
		require_once "$base/index.php";
	}
	return true;
}
