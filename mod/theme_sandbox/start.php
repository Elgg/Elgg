<?php
/**
 * Theme Sandbox
 */

elgg_register_event_handler('init', 'system', 'theme_sandbox_init');

function theme_sandbox_init() {

	elgg_register_plugin_hook_handler('register', 'menu:site', '_theme_sandbox_site_menu', 9999);
	elgg_register_plugin_hook_handler('register', 'menu:page', '_theme_sandbox_page_menu');
	
	elgg_register_page_handler('theme_sandbox', 'theme_sandbox_controller');
	
	elgg_register_external_view('theme_sandbox/ajax'); // for lightbox in sandbox
	
	$sandbox_css = elgg_get_simplecache_url('theme_sandbox.css');
	elgg_register_css('dev.theme_sandbox', $sandbox_css);

	elgg_register_ajax_view('theme_sandbox/components/tabs/ajax');
}

/**
 * Register menu items for the site menu
 *
 * @param string $hook
 * @param string $type
 * @param array  $return
 * @param array  $params
 * @return array
 *
 * @access private
 *
 * @since 3.0
 */
function _theme_sandbox_site_menu($hook, $type, $return, $params) {
	if (!elgg_in_context('theme_sandbox') || !elgg_is_admin_logged_in()) {
		return;
	}
	
	$return = [];
	
	$pages = [
		'buttons',
		'components',
		'forms',
		'grid',
		'icons',
		'javascript',
		'layouts',
		'modules',
		'navigation',
		'typography',
	];
	
	foreach ($pages as $page_name) {
		$return[] = \ElggMenuItem::factory([
			'name' => $page_name,
			'text' => elgg_echo("theme_sandbox:$page_name"),
			'href' => elgg_http_add_url_query_elements("theme_sandbox/$page_name", [
				'layout' => get_input('layout'),
			]),
		]);
	}
	
	return $return;
}

/**
 * Register menu items for the page menu
 *
 * @param string $hook
 * @param string $type
 * @param array  $return
 * @param array  $params
 * @return array
 *
 * @access private
 *
 * @since 3.0
 */
function _theme_sandbox_page_menu($hook, $type, $return, $params) {
	if (!elgg_in_context('admin') || !elgg_is_admin_logged_in()) {
		return;
	}
	
	$return[] = \ElggMenuItem::factory([
		'name' => 'develop_tools',
		'text' => elgg_echo('admin:develop_tools'),
		'section' => 'develop',
	]);
	
	$return[] = \ElggMenuItem::factory([
		'name' => 'develop_tools:sandbox',
		'href' => 'theme_sandbox',
		'target' => '_blank',
		'text' => elgg_echo('admin:develop_tools:sandbox'),
		'parent_name' => 'develop_tools',
		'section' => 'develop',
	]);
	
	return $return;
}

/**
 * Serve the theme sandbox pages
 *
 * @param array $page
 * @return bool
 */
function theme_sandbox_controller($page) {
	if (!isset($page[0])) {
		forward('theme_sandbox/intro');
	}

	echo elgg_view_resource('theme_sandbox', [
		'page' => $page[0],
	]);
	return true;
}
