<?php
/**
 * Elgg developer tools
 */

// we want to run this as soon as possible - other plugins should not need to do this
developers_process_settings();

elgg_register_event_handler('init', 'system', 'developers_init');

function developers_init() {
	elgg_register_event_handler('pagesetup', 'system', 'developers_setup_menu');

	elgg_extend_view('admin.css', 'developers/css');
	elgg_extend_view('elgg.css', 'developers/css');

	elgg_register_page_handler('theme_sandbox', 'developers_theme_sandbox_controller');
	elgg_register_page_handler('developers_ajax_demo', 'developers_ajax_demo_controller');

	elgg_register_external_view('developers/ajax'); // for lightbox in sandbox
	elgg_register_ajax_view('developers/ajax_demo.html');
	$sandbox_css = elgg_get_simplecache_url('theme_sandbox.css');
	elgg_register_css('dev.theme_sandbox', $sandbox_css);

	$action_base = __DIR__ . '/actions/developers';
	elgg_register_action('developers/settings', "$action_base/settings.php", 'admin');
	elgg_register_action('developers/ajax_demo', "$action_base/ajax_demo.php", 'admin');
	elgg_register_action('developers/entity_explorer_delete', "$action_base/entity_explorer_delete.php", 'admin');

	elgg_register_ajax_view('forms/developers/ajax_demo');
	elgg_register_ajax_view('theme_sandbox/components/tabs/ajax_demo');
}

function developers_process_settings() {
	$settings = elgg_get_plugin_from_id('developers')->getAllSettings();

	ini_set('display_errors', (int)!empty($settings['display_errors']));

	if (!empty($settings['screen_log'])) {
		// don't show in action/simplecache
		$path = substr(current_page_url(), strlen(elgg_get_site_url()));
		if (!preg_match('~^(cache|action)/~', $path)) {
			$cache = new ElggLogCache();
			elgg_set_config('log_cache', $cache);
			elgg_register_plugin_hook_handler('debug', 'log', array($cache, 'insertDump'));
			elgg_register_plugin_hook_handler('view_vars', 'page/elements/html', function($hook, $type, $vars, $params) {
				$vars['body'] .= elgg_view('developers/log');
				return $vars;
			});
		}
	}

	if (!empty($settings['show_strings'])) {
		// Beginning and end to make sure both early-rendered and late-loaded translations get included
		elgg_register_event_handler('init', 'system', 'developers_decorate_all_translations', 1);
		elgg_register_event_handler('init', 'system', 'developers_decorate_all_translations', 1000);
	}

	if (!empty($settings['show_modules'])) {
		elgg_require_js('elgg/dev/amd_monitor');
	}

	if (!empty($settings['wrap_views'])) {
		elgg_register_plugin_hook_handler('view', 'all', 'developers_wrap_views', 600);
	}

	if (!empty($settings['log_events'])) {
		elgg_register_event_handler('all', 'all', 'developers_log_events', 1);
		elgg_register_plugin_hook_handler('all', 'all', 'developers_log_events', 1);
	}

	if (!empty($settings['show_gear']) && elgg_is_admin_logged_in() && !elgg_in_context('admin')) {
		elgg_require_js('elgg/dev/gear');
		elgg_register_ajax_view('developers/gear_popup');
		elgg_register_simplecache_view('elgg/dev/gear.html');

		// TODO use ::class in 2.0
		$handler = ['Elgg\DevelopersPlugin\Hooks', 'alterMenuSectionVars'];
		elgg_register_plugin_hook_handler('view_vars', 'navigation/menu/elements/section', $handler);

		$handler = ['Elgg\DevelopersPlugin\Hooks', 'alterMenuSections'];
		elgg_register_plugin_hook_handler('view', 'navigation/menu/elements/section', $handler);
	}
}

function developers_setup_menu() {
	if (elgg_in_context('admin') && elgg_is_admin_logged_in()) {
		elgg_register_admin_menu_item('develop', 'inspect');
		elgg_register_admin_menu_item('develop', 'sandbox', 'develop_tools');
		elgg_register_admin_menu_item('develop', 'unit_tests', 'develop_tools');
		elgg_register_admin_menu_item('develop', 'entity_explorer', 'develop_tools');

		elgg_register_menu_item('page', array(
			'name' => 'dev_settings',
			'href' => 'admin/developers/settings',
			'text' => elgg_echo('settings'),
			'context' => 'admin',
			'priority' => 10,
			'section' => 'develop'
		));
		
		$inspect_options = developers_get_inspect_options();
		foreach ($inspect_options as $key => $value) {
			elgg_register_menu_item('page', array(
				'name' => 'dev_inspect_' . elgg_get_friendly_title($key),
				'href' => "admin/develop_tools/inspect?" . http_build_query([
					'inspect_type' => $key,
				]),
				'text' => $value,
				'context' => 'admin',
				'section' => 'develop',
				'parent_name' => 'inspect'
			));
		}
	}
}

/**
 * Adds debug info to all translatable strings.
 */
function developers_decorate_all_translations() {
	$language = get_current_language();
	_developers_decorate_translations($language);
	_developers_decorate_translations('en');
}

/**
 * Appends " ($key)" to all strings for the given language.
 *
 * This function checks if the suffix has already been added so it is idempotent
 *
 * @param string $language Language code like "en"
 */
function _developers_decorate_translations($language) {
	foreach ($GLOBALS['_ELGG']->translations[$language] as $key => &$value) {
		$needle = " ($key)";
		
		// if $value doesn't already end with " ($key)", append it
		if (substr($value, -strlen($needle)) !== $needle) {
			$value .= $needle;
		}
	}
}

/**
 * Clear all the strings so the raw descriptor strings are displayed
 *
 * @deprecated Superceded by developers_decorate_all_translations
 */
function developers_clear_strings() {
	$language = get_current_language();
	$GLOBALS['_ELGG']->translations[$language] = array();
	$GLOBALS['_ELGG']->translations['en'] = array();
}

/**
 * Post-process a view to add wrapper comments to it
 *
 * 1. Only process views served with the 'default' viewtype.
 * 2. Does not wrap views that are not HTML.
 * 4. Does not wrap input and output views (why?).
 * 5. Does not wrap html head or the primary page shells
 *
 * @warning this will break views in the default viewtype that return non-HTML data
 * that do not match the above restrictions.
 */
function developers_wrap_views($hook, $type, $result, $params) {
	if (elgg_get_viewtype() != "default") {
		return;
	}

	$excluded_bases = array('resources', 'input', 'output', 'embed', 'icon', 'json', 'xml');

	$excluded_views = array(
		'page/default',
		'page/admin',
		'page/elements/head',
	);

	$view = $params['view'];

	$view_hierarchy = explode('/',$view);
	if (in_array($view_hierarchy[0], $excluded_bases)) {
		return;
	}

	if (in_array($view, $excluded_views)) {
		return;
	}
	
	if ((new \SplFileInfo($view))->getExtension()) {
		return;
	}

	if ($result) {
		$result = "<!-- developers:begin $view -->$result<!-- developers:end $view -->";
	}

	return $result;
}

/**
 * Log the events and plugin hooks
 */
function developers_log_events($name, $type) {

	// filter out some very common events
	if ($name == 'view' || $name == 'display' || $name == 'log' || $name == 'debug') {
		return;
	}
	if ($name == 'session:get' || $name == 'validate') {
		return;
	}

	// 0 => this function
	// 1 => call_user_func_array
	// 2 => hook class trigger
	$stack = debug_backtrace();
	if (isset($stack[2]['class']) && $stack[2]['class'] == 'Elgg\EventsService') {
		$event_type = 'Event';
	} else {
		$event_type = 'Plugin hook';
	}

	if ($stack[3]['function'] == 'elgg_trigger_event' || $stack[3]['function'] == 'elgg_trigger_plugin_hook') {
		$index = 4;
	} else {
		$index = 3;
	}
	if (isset($stack[$index]['class'])) {
		$function = $stack[$index]['class'] . '::' . $stack[$index]['function'] . '()';
	} else {
		$function = $stack[$index]['function'] . '()';
	}
	if ($function == 'require_once()' || $function == 'include_once()') {
		$function = $stack[$index]['file'];
	}

	$msg = elgg_echo('developers:event_log_msg', array(
		$event_type,
		$name,
		$type,
		$function,
	));
	elgg_dump($msg, false);

	unset($stack);
}

/**
 * Serve the theme sandbox pages
 *
 * @param array $page
 * @return bool
 */
function developers_theme_sandbox_controller($page) {
	if (!isset($page[0])) {
		forward('theme_sandbox/intro');
	}

	echo elgg_view_resource('theme_sandbox', [
		'page' => $page[0],
	]);
	return true;
}

function developers_ajax_demo_controller() {
	echo elgg_view_resource('developers/ajax_demo');
	return true;
}

/**
 * Get the available inspect options
 *
 * @return array
 */
function developers_get_inspect_options() {
	$options = array(
		'Actions' => elgg_echo('developers:inspect:actions'),
		'Events' => elgg_echo('developers:inspect:events'),
		'Menus' => elgg_echo('developers:inspect:menus'),
		'Plugin Hooks' => elgg_echo('developers:inspect:pluginhooks'),
		'Simple Cache' => elgg_echo('developers:inspect:simplecache'),
		'Views' => elgg_echo('developers:inspect:views'),
		'Widgets' => elgg_echo('developers:inspect:widgets'),
	);
	
	if (elgg_is_active_plugin('web_services')) {
		$options['Web Services'] = elgg_echo('developers:inspect:webservices');
	}
	
	ksort($options);
	
	return $options;
}
