<?php

namespace Elgg\Developers;

use Elgg\DefaultPluginBootstrap;
use Elgg\I18n\NullTranslator;

/**
 * Bootstraps the plugin
 *
 * @since 4.0
 */
class Bootstrap extends DefaultPluginBootstrap {
	
	/**
	 * {@inheritdoc}
	 */
	public function boot() {
		$this->processSettings();
	}

	/**
	 * Process plugin settings before plugins are started
	 *
	 * @return void
	 */
	protected function processSettings(): void {
		$elgg = $this->elgg();
		$events = $elgg->events;
		
		$settings = $this->plugin()->getAllSettings();

		$display_errors = (int) !empty($settings['display_errors']);
		if ($display_errors) {
			ini_set('display_errors', $display_errors);
		}

		if (!empty($settings['screen_log']) && (elgg_get_viewtype() === 'default') && !elgg_is_cli() && !elgg_is_xhr()) {
			// don't show in action/simplecache
			$path = elgg_substr(elgg_get_current_url(), elgg_strlen(elgg_get_site_url()));
			if (!preg_match('~^(cache|action)/~', $path)) {
				// Write to JSON file to not take up memory See #11886
				$uid = substr(hash('md5', uniqid('', true)), 0, 10);
				$log_file = elgg_sanitize_path(elgg_get_data_path() . "logs/screen/{$uid}.html", false);
				$elgg->config->log_cache = $log_file;

				$handler = new \Monolog\Handler\StreamHandler(
					$log_file,
					$elgg->logger->getLevel()
				);
	
				$formatter = new \Elgg\Developers\ConsoleLogFormatter();
				$handler->setFormatter($formatter);
	
				$elgg->logger->pushHandler($handler);
	
				$handler->pushProcessor(new \Elgg\Logger\BacktraceProcessor());

				$events->registerHandler('shutdown', 'system', function() use ($handler, $elgg) {
					$handler->close();

					echo elgg_format_element('script', [], $this->getPageStats());

					$log_file = $elgg->config->log_cache;
					if (is_file($log_file)) {
						echo elgg_format_element('script', [], file_get_contents($log_file));
						unlink($log_file);
					}
				}, 1000);
			}
		}
	
		// setting a custom translator
		$show_strings = (int) elgg_extract('show_strings', $settings, 0);
		if (in_array($show_strings, [1,2])) {
			$old_translator = elgg()->translator;
			
			if ($show_strings === 1) {
				$translator = new AppendTranslator(_elgg_services()->config, _elgg_services()->locale);
			} elseif ($show_strings === 2) {
				$translator = new NullTranslator(_elgg_services()->config, _elgg_services()->locale);
			}
			
			foreach ($old_translator->getLanguagePaths() as $path) {
				$translator->registerLanguagePath($path);
			}
			
			foreach ($old_translator->getLoadedTranslations() as $country_code => $language_array) {
				$translator->addTranslation($country_code, $language_array);
			}
			
			_elgg_services()->set('translator', $translator);
			elgg()->set('translator', $translator);
		}
	
		if (!empty($settings['wrap_views'])) {
			$events->registerHandler('view', 'all', __NAMESPACE__ . '\ViewWrapperHandler', 600);
		}
	
		if (!empty($settings['log_events'])) {
			$events->registerHandler('all', 'all', __NAMESPACE__ . '\HandlerLogger::trackEvent', 1);
		}
		
		if (!empty($settings['block_email'])) {
			$events->registerHandler('transport', 'system:email', __NAMESPACE__ . '\Events::blockOutgoingEmails');
			
			if (!empty($settings['forward_email'])) {
				$events->registerHandler('prepare', 'system:email', __NAMESPACE__ . '\Events::setForwardEmailAddress');
			}
		}
	
		if (!empty($settings['enable_error_log'])) {
			$handler = new \Monolog\Handler\RotatingFileHandler(
				elgg_sanitize_path(elgg_get_data_path() . 'logs/html/errors.html', false),
				elgg_extract('error_log_max_files', $settings, 60),
				\Monolog\Level::Error
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

	/**
	 * Returns page statistics to be used in developer console log
	 *
	 * @return string
	 */
	protected function getPageStats(): string {

		$elapsed = microtime(true) - elgg_extract('START_MICROTIME', $GLOBALS);

		$boot_cache_rebuilt = !elgg_get_config('_boot_cache_hit') ? elgg_echo('option:yes') : elgg_echo('option:no');
		$system_cache_enabled = _elgg_services()->systemCache->isEnabled() ? elgg_echo('option:yes') : elgg_echo('option:no');

		$request_stats = [
			elgg_echo('developers:elapsed_time') => sprintf('%1.3f', $elapsed),
			elgg_echo('developers:log_queries') => _elgg_services()->db->getQueryCount(),
			elgg_echo('developers:boot_cache_rebuilt') => $boot_cache_rebuilt,
			elgg_echo('developers:label:system_cache') => $system_cache_enabled,
		];

		$result = 'console.group("' . elgg_echo('developers:request_stats') . '");';
		$result .= 'console.table(' . json_encode($request_stats) . ');';
		$result .= 'console.groupEnd();';

		return $result;
	}
}
