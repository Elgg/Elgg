<?php
/**
 * Elgg developer tools
 */

// we want to run this as soon as possible - other plugins should not need to do this
developers_process_settings();

elgg_register_event_handler('init', 'system', 'developers_init');

function developers_init() {
	elgg_register_event_handler('pagesetup', 'system', 'developers_setup_menu');

	elgg_extend_view('css/admin', 'developers/css');
	elgg_extend_view('css/elgg', 'developers/css');

	elgg_register_page_handler('theme_preview', 'developers_theme_preview_controller');

	$action_base = elgg_get_plugins_path() . 'developers/actions/developers';
	elgg_register_action('developers/settings', "$action_base/settings.php", 'admin');
	elgg_register_action('developers/inspect', "$action_base/inspect.php", 'admin');

	elgg_register_js('jquery.jstree', 'mod/developers/vendors/jsTree/jquery.jstree.js', 'footer');
	elgg_register_css('jquery.jstree', 'mod/developers/vendors/jsTree/themes/default/style.css');

	elgg_load_js('jquery.form');

	elgg_register_simplecache_view('js/elgg/dev');
	elgg_register_js('elgg.dev', elgg_get_simplecache_url('js', 'elgg/dev'), 'footer');
	elgg_load_js('elgg.dev');
}

function developers_process_settings() {
	if (elgg_get_plugin_setting('display_errors', 'developers') == 1) {
		ini_set('display_errors', 1);
	} else {
		ini_set('display_errors', 0);
	}

	if (elgg_get_plugin_setting('query_count', 'developers') == 1) {
		$developers = ElggDevelopersService::getInstance();
		elgg_register_event_handler('plugins_boot', 'system', array($developers, 'collectQueryData'), 1000);
		elgg_register_event_handler('init', 'system', array($developers, 'collectQueryData'), 1000);
		elgg_register_event_handler('ready', 'system', array($developers, 'collectQueryData'), 1000);
		elgg_register_plugin_hook_handler('view', 'page/elements/foot', array($developers, 'displayQueryData'), 1000);
	}

	if (elgg_get_plugin_setting('screen_log', 'developers') == 1) {
		$cache = ElggDevelopersService::getInstance()->getLog();
		elgg_register_plugin_hook_handler('debug', 'log', array($cache, 'insertDump'));
		elgg_extend_view('page/elements/foot', 'developers/log');
	}

	if (elgg_get_plugin_setting('show_strings', 'developers') == 1) {
		$developers = ElggDevelopersService::getInstance();
		// first and last in case a plugin registers a translation in an init method
		elgg_register_event_handler('init', 'system', array($developers, 'clearStrings'), 1000);
		elgg_register_event_handler('init', 'system', array($developers, 'clearStrings'), 1);
	}

	if (elgg_get_plugin_setting('wrap_views', 'developers') == 1) {
		$developers = ElggDevelopersService::getInstance();
		elgg_register_plugin_hook_handler('view', 'all', array($developers, 'wrapViews'));
	}

	if (elgg_get_plugin_setting('log_events', 'developers') == 1) {
		$developers = ElggDevelopersService::getInstance();
		elgg_register_event_handler('all', 'all', array($developers, 'logEvents'), 1);
		elgg_register_plugin_hook_handler('all', 'all', array($developers, 'logEvents'), 1);
	}
}

function developers_setup_menu() {
	if (elgg_in_context('admin')) {
		elgg_register_admin_menu_item('develop', 'inspect', 'develop_tools');
		elgg_register_admin_menu_item('develop', 'preview', 'develop_tools');
		elgg_register_admin_menu_item('develop', 'unit_tests', 'develop_tools');

		elgg_register_menu_item('page', array(
			'name' => 'dev_settings',
			'href' => 'admin/developers/settings',
			'text' => elgg_echo('settings'),
			'context' => 'admin',
			'priority' => 10,
			'section' => 'develop'
		));
	}
}

/**
 * Serve the theme preview pages
 *
 * @param array $page
 * @return bool
 */
function developers_theme_preview_controller($page) {
	$developers = ElggDevelopersService::getInstance();
	return $developers->themePreviewController($page);
}
