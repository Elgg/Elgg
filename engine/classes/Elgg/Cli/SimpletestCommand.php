<?php

namespace Elgg\Cli;

use Elgg\Application;
use Elgg\Config;
use Exception;
use RuntimeException;
use Symfony\Component\Console\Input\InputOption;
use TestSuite;
use TextReporter;

/**
 * elgg-cli simpletest [--config] [--plugins]
 */
class SimpletestCommand extends Command {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('simpletest')
			->setDescription('Run simpletest test suite')
			->addOption('config', 'c', InputOption::VALUE_OPTIONAL,
				'Path to settings file that the Elgg Application should be bootstrapped with'
			)
			->addOption('plugins', 'p', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
				'A list of plugins to enable for testing or "all" to enable all plugins'
			);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function command() {

		if (!class_exists('ElggCoreUnitTest')) {
			elgg_log('You must install your Elgg application using "composer install --dev"', 'ERROR');

			return 1;
		}

		// Disable maximum execution time.
		// Tests take a while...
		set_time_limit(0);

		ob_start();

		$error = 0;

		try {
			$settings_path = $this->option('config');
			if ($settings_path) {
				$sp = _elgg_services();
				$app = Application::factory([
					'settings_path' => $settings_path,
					'service_provider' => $sp,
				]);
				Application::setInstance($app);
			}

			_elgg_services()->hooks->registerHandler('forward', 'all', [
				$this,
				'dumpRegisters'
			]);
			_elgg_services()->hooks->registerHandler('send:before', 'http_response', [
				$this,
				'dumpData'
			]);

			// turn off system log
			_elgg_services()->hooks->unregisterHandler('all', 'all', 'system_log_listener');
			_elgg_services()->hooks->unregisterHandler('log', 'systemlog', 'system_log_default_logger');

			$admin = array_shift(elgg_get_admins(['limit' => 1]));
			if (!login($admin)) {
				throw new RuntimeException("Failed to login as administrator.");
			}

			// disable emails
			elgg_set_email_transport(new \Zend\Mail\Transport\InMemory());

			$plugins = $this->option('plugins');
			if (in_array('all', $plugins)) {
				$plugins = [];
				$plugin_entities = elgg_get_plugins('inactive');
				foreach ($plugin_entities as $plugin_entity) {
					$plugins[] = $plugin_entity->getID();
				}
			} else if (empty($plugins)) {
				// plugins that contain unit tests
				$plugins = [
					'groups',
					'thewire',
					'web_services'
				];
			}

			// activate plugins that are not activated on install
			foreach ($plugins as $key => $id) {
				$plugin = elgg_get_plugin_from_id($id);
				if (!$plugin || $plugin->isActive()) {
					unset($plugins[$key]);
					continue;
				}
				$plugin->activate();
			}

			$suite = new TestSuite('Elgg Core Unit Tests');

			$test_cases = _elgg_services()->hooks->trigger('unit_test', 'system', null, []);
			foreach ($test_cases as $file) {
				if (substr($file, -4, 4) === '.php') {
					$suite->addFile($file);
				} else if (class_exists($file)) {
					$suite->add($file);
				}
			}

			$start_time = microtime(true);

			$reporter = new TextReporter();
			$result = $suite->Run($reporter);

			// deactivate plugins that were activated for test suite
			foreach ($plugins as $key => $id) {
				$plugin = elgg_get_plugin_from_id($id);
				$plugin->deactivate();
			}

			echo PHP_EOL . sprintf("Time: %.2f seconds, Memory: %.2fMb\n",
					microtime(true) - $start_time,
					memory_get_peak_usage() / 1048576.0 // in megabytes
				) . PHP_EOL;

			if (!$result) {
				throw new RuntimeException('One or more tests have failed');
			}
		} catch (Exception $e) {
			$error = 1;
			elgg_log("Test suite has failed with " . get_class($e) . ': ' . $e->getMessage(), 'ERROR');
		}

		forward();

		$this->write(STDOUT, ob_get_clean());

		return $error;
	}

}