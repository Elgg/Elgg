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
		$elgg = $this->elgg();
		$hooks = $elgg->hooks;
		$events = $elgg->events;
		
		$settings = $this->plugin()->getAllSettings();
	
		ini_set('display_errors', (int) !empty($settings['display_errors']));
	
		if (!empty($settings['screen_log']) && (elgg_get_viewtype() === 'default') && !\Elgg\Application::isCli()) {
			// don't show in action/simplecache
			$path = elgg_substr(current_page_url(), elgg_strlen(elgg_get_site_url()));
			if (!preg_match('~^(cache|action)/~', $path)) {
				// Write to JSON file to not take up memory See #11886
				$uid = substr(hash('md5', uniqid('', true)), 0, 10);
				$log_file = \Elgg\Project\Paths::sanitize(elgg_get_config('dataroot') . "logs/screen/$uid.html", false);
				$elgg->config->log_cache = $log_file;
	
				$handler = new \Monolog\Handler\StreamHandler(
					$log_file,
					$elgg->logger->getLevel()
				);
	
				$formatter = new \Elgg\Developers\ErrorLogHtmlFormatter();
				$handler->setFormatter($formatter);
	
				$elgg->logger->pushHandler($handler);
	
				$handler->pushProcessor(new \Elgg\Logger\BacktraceProcessor());
	
				$hooks->registerHandler('view_vars', 'page/elements/html', function(\Elgg\Hook $hook)  use ($handler) {
					$vars = $hook->getValue();

					// prevent logs from showing up in html mails
					if (elgg_extract('email', $vars) instanceof \Elgg\Email) {
						return;
					}
					
					$handler->close();
					
					$vars['body'] .= elgg_view('developers/log');
					
					return $vars;
				});
	
				$events->registerHandler('shutdown', 'system', function() use ($handler, $elgg) {
					// Prevent errors in cli
					$handler->close();
					
					$log_file = $elgg->config->log_cache;
					if (is_file($log_file)) {
						unlink($log_file);
					}
				}, 1000);
			}
		}
	
		if (!empty($settings['show_strings'])) {
			// Beginning and end to make sure both early-rendered and late-loaded translations get included
			$events->registerHandler('init', 'system', 'developers_decorate_all_translations', 1);
			$events->registerHandler('init', 'system', 'developers_decorate_all_translations', 1000);
		}
	
		if (!empty($settings['show_modules'])) {
			elgg_require_js('elgg/dev/amd_monitor');
		}
	
		if (!empty($settings['wrap_views'])) {
			$hooks->registerHandler('view', 'all', 'developers_wrap_views', 600);
		}
	
		if (!empty($settings['log_events'])) {
			$events->registerHandler('all', 'all', __NAMESPACE__ . '\HandlerLogger::trackEvent', 1);
			$hooks->registerHandler('all', 'all', __NAMESPACE__ . '\HandlerLogger::trackHook', 1);
		}
	
		if (!empty($settings['show_gear']) && elgg_is_admin_logged_in() && !elgg_in_context('admin')) {
			elgg_require_js('elgg/dev/gear');
			elgg_register_ajax_view('developers/gear_popup');
			elgg_register_simplecache_view('elgg/dev/gear.html');
	
			$hooks->registerHandler('view_vars', 'navigation/menu/elements/section', __NAMESPACE__ . '\Hooks::alterMenuSectionVars');
			$hooks->registerHandler('view', 'navigation/menu/elements/section', __NAMESPACE__ . '\Hooks::alterMenuSections');
			$hooks->registerHandler('view', 'navigation/menu/default', __NAMESPACE__ . '\Hooks::alterMenu');
		}
		
		if (!empty($settings['block_email'])) {
			$hooks->registerHandler('transport', 'system:email', __NAMESPACE__ . '\Hooks::blockOutgoingEmails');
			
			if (!empty($settings['forward_email'])) {
				$hooks->registerHandler('prepare', 'system:email', __NAMESPACE__ . '\Hooks::setForwardEmailAddress');
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
	
			$elgg->logger->pushHandler($handler);
		}
	}
}
