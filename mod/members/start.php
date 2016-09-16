<?php

/**
 * Members plugin initialization
 *
 * To add a new page:
 * - Add a new tab using 'filter_tabs','members' hook
 * - Add a view corresponding to the tab name in members/listing/<tab_name>
 */
elgg_register_event_handler('init', 'system', 'members_init');

/**
 * Initialize page handler and site menu item
 */
function members_init() {
	elgg_register_page_handler('members', 'members_page_handler');

	elgg_register_menu_item('site', [
		'name' => 'members',
		'text' => elgg_echo('members'),
		'href' => 'members',
	]);

	elgg_register_plugin_hook_handler('members:list', 'all', '_members_render_list');
	elgg_register_plugin_hook_handler('members:config', 'tabs', '_members_prepare_tabs');

	elgg_register_plugin_hook_handler('filter_tabs', 'members', 'members_prepare_filter_tabs', 400);
}

/**
 * Render a list of members
 *
 * @todo: in 3.0 remove this handler and move the logic in the resource view
 * 
 * @param string $hook   "members:list"
 * @param string $type   "all"
 * @param mixed  $return List
 * @param array  $params Hook params
 * @return mixed
 * @access private
 */
function _members_render_list($hook, $type, $return, $params) {
	if (isset($return)) {
		elgg_deprecated_notice("'members:list','$type' hook has been deprecated. Use 'members/listing/$type' view instead", '2.3');
	}

	if (elgg_view_exists("members/listing/$type")) {
		return elgg_view("members/listing/$type", $params);
	}
}

/**
 * Prepare default tabs
 *
 * @todo: remove in 3.0
 *
 * @param string $hook   "members:config"
 * @param string $type   "tabs"
 * @param array  $return Tabs
 * @param array  $params Hook params
 * @return array
 */
function _members_prepare_tabs($hook, $type, $return, $params) {

	if (elgg_extract('__ignore_defaults', (array) $params)) {
		return;
	}

	elgg_deprecated_notice("'members:config','tabs' hook has been deprecated. Use 'filter_tabs','members' instead", '2.3');

	$defaults = [
		'newest' => [
			'title' => elgg_echo('sort:newest'),
			'url' => "members/newest",
		],
		'alpha' => [
			'title' => elgg_echo('sort:alpha'),
			'url' => "members/alpha",
		],
		'popular' => [
			'title' => elgg_echo('sort:popular'),
			'url' => "members/popular",
		],
		'online' => [
			'title' => elgg_echo('members:label:online'),
			'url' => "members/online",
		],
	];

	return $defaults + $return;
}

/**
 * Prepare filter tabs
 *
 * @todo in 3.0, remove 'members:config' hook call
 * 
 * @param string $hook   "filter_tabs"
 * @param string $type   "members"
 * @param array  $return Tabs
 * @param array  $params Hook params
 * @return array
 */
function members_prepare_filter_tabs($hook, $type, $return, $params) {

	$defaults = [
		'newest' => [
			'text' => elgg_echo('sort:newest'),
			'href' => "members/newest",
		],
		'alpha' => [
			'text' => elgg_echo('sort:alpha'),
			'href' => "members/alpha",
		],
		'popular' => [
			'text' => elgg_echo('sort:popular'),
			'href' => "members/popular",
		],
		'online' => [
			'text' => elgg_echo('members:label:online'),
			'href' => "members/online",
		],
	];

	$params['__ignore_defaults'] = true; // needed to keep hook BC
	$tabs = (array) elgg_trigger_plugin_hook('members:config', 'tabs', $params, $defaults);

	if (!empty($tabs)) {
		foreach ($tabs as $name => $tab) {
			if (!isset($tab['name'])) {
				$tab['name'] = $name;
			}
			if (!isset($tab['text'])) {
				$tab['text'] = $tab['title'];
			}
			if (!isset($tab['href'])) {
				$tab['href'] = $tab['url'];
			}
			$tabs[$name] = $tab;
		}
	}

	return $tabs + $return;
}

/**
 * Returns content for the "popular" page
 *
 * @param string      $hook        "members:list"
 * @param string      $type        "popular"
 * @param string|null $returnvalue list content (null if not set)
 * @param array       $params      array with key "options"
 * @return string
 */
function members_list_popular($hook, $type, $returnvalue, $params) {
	elgg_deprecated_notice(__FUNCTION__ . " has been deprecated and will be removed", '2.3');
	if ($returnvalue !== null) {
		return;
	}
	return elgg_view("members/listing/$type", $params);
}

/**
 * Returns content for the "newest" page
 *
 * @param string      $hook        "members:list"
 * @param string      $type        "newest"
 * @param string|null $returnvalue list content (null if not set)
 * @param array       $params      array with key "options"
 * @return string
 */
function members_list_newest($hook, $type, $returnvalue, $params) {
	elgg_deprecated_notice(__FUNCTION__ . " has been deprecated and will be removed", '2.3');
	if ($returnvalue !== null) {
		return;
	}
	return elgg_view("members/listing/$type", $params);
}

/**
 * Returns content for the "online" page
 *
 * @param string      $hook        "members:list"
 * @param string      $type        "online"
 * @param string|null $returnvalue list content (null if not set)
 * @param array       $params      array with key "options"
 * @return string
 */
function members_list_online($hook, $type, $returnvalue, $params) {
	elgg_deprecated_notice(__FUNCTION__ . " has been deprecated and will be removed", '2.3');
	if ($returnvalue !== null) {
		return;
	}
	return elgg_view("members/listing/$type", $params);
}

/**
 * Returns content for the "alphabetical" page
 *
 * @param string      $hook        "members:list"
 * @param string      $type        "alpha"
 * @param string|null $returnvalue list content (null if not set)
 * @param array       $params      array with key "options"
 * @return string
 */
function members_list_alpha($hook, $type, $returnvalue, $params) {
	if ($returnvalue !== null) {
		return;
	}

	elgg_deprecated_notice(__FUNCTION__ . " has been deprecated and will be removed", '2.3');
	if ($returnvalue !== null) {
		return;
	}
	return elgg_view("members/listing/$type", $params);
}

/**
 * Appends "popular" tab to the navigation
 *
 * @param string $hook        "members:config"
 * @param string $type        "tabs"
 * @param array  $returnvalue array that build navigation tabs
 * @param array  $params      unused
 * @return array
 */
function members_nav_popular($hook, $type, $returnvalue, $params) {
	elgg_deprecated_notice(__FUNCTION__ . ' has been deprecated and will be removed', '2.3');
	$returnvalue['popular'] = array(
		'title' => elgg_echo('sort:popular'),
		'url' => "members/popular",
	);
	return $returnvalue;
}

/**
 * Appends "newest" tab to the navigation
 *
 * @param string $hook        "members:config"
 * @param string $type        "tabs"
 * @param array  $returnvalue array that build navigation tabs
 * @param array  $params      unused
 * @return array
 * @deprecated 2.3
 */
function members_nav_newest($hook, $type, $returnvalue, $params) {
	elgg_deprecated_notice(__FUNCTION__ . ' has been deprecated and will be removed', '2.3');
	$returnvalue['newest'] = array(
		'title' => elgg_echo('sort:newest'),
		'url' => "members/newest",
	);
	return $returnvalue;
}

/**
 * Appends "online" tab to the navigation
 *
 * @param string $hook        "members:config"
 * @param string $type        "tabs"
 * @param array  $returnvalue array that build navigation tabs
 * @param array  $params      unused
 * @return array
 */
function members_nav_online($hook, $type, $returnvalue, $params) {
	elgg_deprecated_notice(__FUNCTION__ . ' has been deprecated and will be removed', '2.3');
	$returnvalue['online'] = array(
		'title' => elgg_echo('members:label:online'),
		'url' => "members/online",
	);
	return $returnvalue;
}

/**
 * Appends "alphabetical" tab to the navigation
 *
 * @param string $hook        "members:config"
 * @param string $type        "tabs"
 * @param array  $returnvalue array that build navigation tabs
 * @param array  $params      unused
 * @return array
 */
function members_nav_alpha($hook, $type, $returnvalue, $params) {
	elgg_deprecated_notice(__FUNCTION__ . ' has been deprecated and will be removed', '2.3');
	$returnvalue['alpha'] = array(
		'title' => elgg_echo('sort:alpha'),
		'url' => "members/alpha",
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
	if (empty($page[0])) {
		$page[0] = 'newest';
	}

	if ($page[0] == 'search') {
		echo elgg_view_resource('members/search');
	} else {
		echo elgg_view_resource('members/index', [
			'page' => $page[0],
		]);
	}
	return true;
}
