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

	elgg_register_page_handler('theme_sandbox', 'developers_theme_sandbox_controller');
	elgg_register_external_view('developers/ajax'); // for lightbox in sandbox
	$sandbox_css = elgg_get_simplecache_url('css', 'theme_sandbox.css');
	elgg_register_css('dev.theme_sandbox', $sandbox_css);

	$action_base = elgg_get_plugins_path() . 'developers/actions/developers';
	elgg_register_action('developers/settings', "$action_base/settings.php", 'admin');

	elgg_define_js('jquery.jstree', array(
		'src' => '/mod/developers/vendors/jsTree/jquery.jstree.js',
		'exports' => 'jQuery.fn.jstree',
		'deps' => array('jquery'),
	));
	elgg_register_css('jquery.jstree', '/mod/developers/vendors/jsTree/themes/default/style.css');

	elgg_require_js('elgg/dev');
}

function developers_process_settings() {
	$settings = elgg_get_plugin_from_id('developers')->getAllSettings();

	ini_set('display_errors', (int)!empty($settings['display_errors']));

	if (!empty($settings['screen_log'])) {
		$cache = new ElggLogCache();
		elgg_set_config('log_cache', $cache);
		elgg_register_plugin_hook_handler('debug', 'log', array($cache, 'insertDump'));
		elgg_register_plugin_hook_handler('view_vars', 'page/elements/html', function($hook, $type, $vars, $params) {
			$vars['body'] .= elgg_view('developers/log');
			return $vars;
		});
	}

	if (!empty($settings['show_strings'])) {
		// first and last in case a plugin registers a translation in an init method
		elgg_register_event_handler('init', 'system', 'developers_clear_strings', 1000);
		elgg_register_event_handler('init', 'system', 'developers_clear_strings', 1);
	}

	if (!empty($settings['show_modules'])) {
		elgg_require_js('elgg/dev/amd_monitor');
	}

	if (!empty($settings['wrap_views'])) {
		elgg_register_plugin_hook_handler('view', 'all', 'developers_wrap_views');
	}

	if (!empty($settings['log_events'])) {
		elgg_register_event_handler('all', 'all', 'developers_log_events', 1);
		elgg_register_plugin_hook_handler('all', 'all', 'developers_log_events', 1);
	}

	if (!empty($settings['show_gear']) && elgg_is_admin_logged_in() && !elgg_in_context('admin')) {
		elgg_require_js('elgg/dev/gear');
		elgg_load_js('lightbox');
		elgg_load_css('lightbox');
		elgg_register_ajax_view('developers/gear_popup');

		// TODO use ::class in 2.0
		$handler = ['Elgg\DevelopersPlugin\Hooks', 'alterMenuSectionVars'];
		elgg_register_plugin_hook_handler('view_vars', 'navigation/menu/elements/section', $handler);

		$handler = ['Elgg\DevelopersPlugin\Hooks', 'alterMenuSections'];
		elgg_register_plugin_hook_handler('view', 'navigation/menu/elements/section', $handler);
	}
}

function developers_setup_menu() {
	if (elgg_in_context('admin')) {
		elgg_register_admin_menu_item('develop', 'inspect');
		elgg_register_admin_menu_item('develop', 'sandbox', 'develop_tools');
		elgg_register_admin_menu_item('develop', 'unit_tests', 'develop_tools');

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
				'href' => "admin/develop_tools/inspect?inspect_type={$key}",
				'text' => $value,
				'context' => 'admin',
				'section' => 'develop',
				'parent_name' => 'inspect'
			));
		}
	}
}

/**
 * Clear all the strings so the raw descriptor strings are displayed
 */
function developers_clear_strings() {
	global $CONFIG;

	$language = get_language();
	$CONFIG->translations[$language] = array();
	$CONFIG->translations['en'] = array();
}

/**
 * Post-process a view to add wrapper comments to it
 * 
 * 1. Only process views served with the 'default' viewtype.
 * 2. Does not wrap views that begin with js/ or css/ as they are not HTML.
 * 3. Does not wrap views that are images (start with icon/). Is this still true?
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

	$excluded_bases = array('css', 'js', 'input', 'output', 'embed', 'icon', 'json', 'xml');

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

	elgg_load_css('dev.theme_sandbox');

	$pages = array(
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
	);
	
	foreach ($pages as $page_name) {
		elgg_register_menu_item('theme_sandbox', array(
			'name' => $page_name,
			'text' => elgg_echo("theme_sandbox:$page_name"),
			'href' => "theme_sandbox/$page_name",
		));
	}

	elgg_require_js('elgg/dev/theme_sandbox');

	$title = elgg_echo("theme_sandbox:{$page[0]}");
	$body =  elgg_view("theme_sandbox/{$page[0]}");

	$layout = elgg_view_layout('theme_sandbox', array(
		'title' => $title,
		'content' => $body,
	));

	echo elgg_view_page("Theme Sandbox : $title", $layout, 'theme_sandbox');
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
