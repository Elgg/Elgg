<?php

namespace Elgg\Developers;

use Elgg\DefaultPluginBootstrap;

/**
 * Bootstraps the plugin
 *
 * @since 4.0
 * @internal
 */
class Bootstrap extends DefaultPluginBootstrap {
	
	/**
	 * {@inheritDoc}
	 */
	public function boot() {
		$this->processSettings();
	}
	
	/**
	 * Process plugin settings before plugins are started
	 *
	 * @return void
	 */
	protected function processSettings() {
		$settings = elgg_get_plugin_from_id('developers')->getAllSettings();
	
		ini_set('display_errors', (int) !empty($settings['display_errors']));
	
		if (!empty($settings['screen_log']) && (elgg_get_viewtype() === 'default')) {
			// don't show in action/simplecache
			$path = elgg_substr(current_page_url(), elgg_strlen(elgg_get_site_url()));
			if (!preg_match('~^(cache|action)/~', $path)) {
				// Write to JSON file to not take up memory See #11886
				$uid = substr(hash('md5', uniqid('', true)), 0, 10);
				$log_file = \Elgg\Project\Paths::sanitize(elgg_get_config('dataroot') . "logs/screen/$uid.html", false);
				elgg()->config->log_cache = $log_file;
	
				$handler = new \Monolog\Handler\StreamHandler(
					$log_file,
					elgg()->logger->getLevel()
				);
	
				$formatter = new \Elgg\Developers\ErrorLogHtmlFormatter();
				$handler->setFormatter($formatter);
	
				elgg()->logger->pushHandler($handler);
	
				$handler->pushProcessor(new \Elgg\Logger\BacktraceProcessor());
	
				elgg_register_plugin_hook_handler('view_vars', 'page/elements/html', function(\Elgg\Hook $hook)  use ($handler) {
					$handler->close();
					
					$vars = $hook->getValue();
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
				\Monolog\Logger::ERROR
			);
	
			$formatter = new \Elgg\Developers\ErrorLogHtmlFormatter();
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
}
