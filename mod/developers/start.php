<?php
/**
 * Elgg developer tools
 */

use Elgg\DevelopersPlugin\Hooks;

/**
 * Developers init
 *
 * @return void
 */
function developers_init() {

	elgg_register_plugin_hook_handler('register', 'menu:page', '_developers_page_menu');
		
	elgg_extend_view('admin.css', 'developers/css');
	elgg_extend_view('admin.css', 'admin/develop_tools/error_log.css');
	elgg_extend_view('elgg.css', 'developers/css');
	elgg_extend_view('elgg.css', 'admin/develop_tools/error_log.css');

	elgg_register_external_view('developers/ajax'); // for lightbox in sandbox
	elgg_register_ajax_view('developers/ajax_demo.html');

	elgg_register_ajax_view('forms/developers/ajax_demo');
	elgg_register_ajax_view('theme_sandbox/components/tabs/ajax');
}

/**
 * Process plugin settings before plugins are started
 *
 * @return void
 */
function developers_process_settings() {
	$settings = elgg_get_plugin_from_id('developers')->getAllSettings();

	ini_set('display_errors', (int) !empty($settings['display_errors']));

	if (!empty($settings['screen_log']) && (elgg_get_viewtype() === 'default')) {
		// don't show in action/simplecache
		$path = substr(current_page_url(), strlen(elgg_get_site_url()));
		if (!preg_match('~^(cache|action)/~', $path)) {
			// Write to JSON file to not take up memory See #11886
			$uid = substr(hash('md5', uniqid('', true)), 0, 10);
			$log_file = \Elgg\Project\Paths::sanitize(elgg_get_config('dataroot') . "logs/screen/$uid.html", false);
			elgg()->config->log_cache = $log_file;

			$handler = new \Monolog\Handler\StreamHandler(
				$log_file,
				elgg()->logger->getLevel()
			);

			$formatter = new \Elgg\DevelopersPlugin\ErrorLogHtmlFormatter();
			$handler->setFormatter($formatter);

			elgg()->logger->pushHandler($handler);

			$handler->pushProcessor(new \Elgg\Logger\BacktraceProcessor());

			elgg_register_plugin_hook_handler('view_vars', 'page/elements/html', function($hook, $type, $vars, $params)  use ($handler) {
				$handler->close();

				$vars['body'] .= elgg_view('developers/log');
				return $vars;
			});

			elgg_register_event_handler('shutdown', 'system', function() use ($handler) {
				// Prevent errors in cli
				$handler->close();
				
				$log_file = elgg()->config->log_cache;
				if (is_file($log_file)) {
					unlink($log_file);
				}
			}, 1000);
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

		$handler = [Hooks::class, 'alterMenuSectionVars'];
		elgg_register_plugin_hook_handler('view_vars', 'navigation/menu/elements/section', $handler);

		$handler = [Hooks::class, 'alterMenuSections'];
		elgg_register_plugin_hook_handler('view', 'navigation/menu/elements/section', $handler);

		$handler = [Hooks::class, 'alterMenu'];
		elgg_register_plugin_hook_handler('view', 'navigation/menu/default', $handler);
	}
	
	if (!empty($settings['block_email'])) {
		$handler = [Hooks::class, 'blockOutgoingEmails'];
		elgg_register_plugin_hook_handler('transport', 'system:email', $handler);
		
		if (!empty($settings['forward_email'])) {
			$handler = [Hooks::class, 'setForwardEmailAddress'];
			elgg_register_plugin_hook_handler('prepare', 'system:email', $handler);
		}
	}

	if (!empty($settings['enable_error_log'])) {
		$handler = new \Monolog\Handler\RotatingFileHandler(
			\Elgg\Project\Paths::sanitize(elgg_get_config('dataroot') . 'logs/html/errors.html', false),
			elgg_extract('error_log_max_files', $settings, 60),
			\Psr\Log\LogLevel::ERROR
		);

		$formatter = new \Elgg\DevelopersPlugin\ErrorLogHtmlFormatter();
		$handler->setFormatter($formatter);

		$handler->pushProcessor(new \Monolog\Processor\PsrLogMessageProcessor());
		$handler->pushProcessor(new \Monolog\Processor\MemoryUsageProcessor());
		$handler->pushProcessor(new \Monolog\Processor\MemoryPeakUsageProcessor());
		$handler->pushProcessor(new \Monolog\Processor\ProcessIdProcessor());
		$handler->pushProcessor(new \Monolog\Processor\WebProcessor());
		$handler->pushProcessor(new \Elgg\Logger\BacktraceProcessor());

		elgg()->logger->pushHandler($handler);
	}
}

/**
 * Register menu items for the page menu
 *
 * @param string         $hook   'register'
 * @param string         $type   'menu:page'
 * @param ElggMenuItem[] $return current return value
 * @param array          $params supplied params
 *
 * @return void|ElggMenuItem[]
 *
 * @access private
 * @since 3.0
 */
function _developers_page_menu($hook, $type, $return, $params) {
	if (!elgg_in_context('admin') || !elgg_is_admin_logged_in()) {
		return;
	}
	
	$return[] = \ElggMenuItem::factory([
		'name' => 'dev_settings',
		'href' => 'admin/developers/settings',
		'text' => elgg_echo('settings'),
		'priority' => 10,
		'section' => 'develop',
	]);

	$return[] = \ElggMenuItem::factory([
		'name' => 'error_log',
		'href' => 'admin/develop_tools/error_log',
		'text' => elgg_echo('admin:develop_tools:error_log'),
		'section' => 'develop',
	]);
	
	$return[] = \ElggMenuItem::factory([
		'name' => 'inspect',
		'text' => elgg_echo('admin:inspect'),
		'section' => 'develop',
	]);

	$inspect_options = developers_get_inspect_options();
	foreach ($inspect_options as $key => $value) {
		$return[] = \ElggMenuItem::factory([
			'name' => 'dev_inspect_' . elgg_get_friendly_title($key),
			'href' => "admin/develop_tools/inspect?" . http_build_query([
				'inspect_type' => $key,
			]),
			'text' => $value,
			'section' => 'develop',
			'parent_name' => 'inspect',
		]);
	}
	
	$return[] = \ElggMenuItem::factory([
		'name' => 'develop_tools',
		'text' => elgg_echo('admin:develop_tools'),
		'section' => 'develop',
	]);
	
	$return[] = \ElggMenuItem::factory([
		'name' => 'develop_tools:sandbox',
		'href' => 'theme_sandbox/intro',
		'text' => elgg_echo('admin:develop_tools:sandbox'),
		'parent_name' => 'develop_tools',
		'section' => 'develop',
		'target' => '_blank',
	]);
	
	$return[] = \ElggMenuItem::factory([
		'name' => 'develop_tools:entity_explorer',
		'href' => 'admin/develop_tools/entity_explorer',
		'text' => elgg_echo('admin:develop_tools:entity_explorer'),
		'parent_name' => 'develop_tools',
		'section' => 'develop',
	]);
	
	return $return;
}

/**
 * Adds debug info to all translatable strings
 *
 * @return void
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
 *
 * @return void
 */
function _developers_decorate_translations($language) {
	$translations = _elgg_services()->translator->getLoadedTranslations();

	foreach ($translations[$language] as $key => &$value) {
		$needle = " ($key)";
		
		// if $value doesn't already end with " ($key)", append it
		if (substr($value, -strlen($needle)) !== $needle) {
			$value .= $needle;
		}
	}
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
 *
 * @param string $hook   'view'
 * @param string $type   'all'
 * @param string $result current return value
 * @param mixed  $params supplied params
 *
 * @return void|string
 */
function developers_wrap_views($hook, $type, $result, $params) {
	if (elgg_get_viewtype() != "default") {
		return;
	}
	
	if (stristr(current_page_url(), elgg_normalize_url('cache/'))) {
		return;
	}

	$excluded_bases = ['resources', 'input', 'output', 'embed', 'icon', 'json', 'xml'];

	$excluded_views = [
		'page/default',
		'page/admin',
		'page/elements/head',
	];

	$view = $params['view'];

	$view_hierarchy = explode('/', $view);
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
 *
 * @param string $name the name of the event/hook
 * @param string $type the type of the event/hook
 *
 * @return void
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

	$msg = elgg_echo('developers:event_log_msg', [
		$event_type,
		$name,
		$type,
		$function,
	]);
	elgg_dump($msg, false);

	unset($stack);
}

/**
 * Get the available inspect options
 *
 * @return array
 */
function developers_get_inspect_options() {
	$options = [
		'Actions' => elgg_echo('developers:inspect:actions'),
		'Events' => elgg_echo('developers:inspect:events'),
		'Menus' => elgg_echo('developers:inspect:menus'),
		'Plugin Hooks' => elgg_echo('developers:inspect:pluginhooks'),
		'Routes' => elgg_echo('developers:inspect:routes'),
		'Services' => elgg_echo('developers:inspect:services'),
		'Simple Cache' => elgg_echo('developers:inspect:simplecache'),
		'Views' => elgg_echo('developers:inspect:views'),
		'Widgets' => elgg_echo('developers:inspect:widgets'),
	];
	
	if (elgg_is_active_plugin('web_services')) {
		$options['Web Services'] = elgg_echo('developers:inspect:webservices');
	}
	
	ksort($options);
	
	return $options;
}

return function() {
	// we want to run this as soon as possible - other plugins should not need to do this
	developers_process_settings();

	elgg_register_event_handler('init', 'system', 'developers_init');
};
