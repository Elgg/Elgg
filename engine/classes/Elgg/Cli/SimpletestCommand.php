<?php

namespace Elgg\Cli;

use Elgg\Application;
use Elgg\Project\Paths;
use Symfony\Component\Console\Helper\FormatterHelper;
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
			)
			->addOption('filter', 'f', InputOption::VALUE_OPTIONAL,
				'Only run tests that match filter pattern'
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

		if (!date_default_timezone_get()) {
			date_default_timezone_set('America/Los_Angeles');
		}

		error_reporting(E_ALL | E_STRICT);

		// Disable maximum execution time.
		// Tests take a while...
		set_time_limit(0);

		$settings_path = $this->option('config');
		if (!$settings_path) {
			$settings_path = Paths::elgg() . 'engine/tests/elgg-config/simpletest.php';
		}

		$sp = _elgg_services();
		$app = Application::factory([
			'settings_path' => $settings_path,
			'service_provider' => $sp,
			'handle_exceptions' => false,
			'handle_shutdown' => false,
		]);

		Application::setInstance($app);

		$app->bootCore();

		// turn off system log
		_elgg_services()->hooks->unregisterHandler('all', 'all', 'system_log_listener');
		_elgg_services()->hooks->unregisterHandler('log', 'systemlog', 'system_log_default_logger');

		// disable emails
		elgg_set_email_transport(new \Zend\Mail\Transport\InMemory());

		$plugins = (array) $this->option('plugins');
		if (in_array('all', $plugins)) {
			$plugins = [];
			$plugin_entities = elgg_get_plugins('inactive');
			foreach ($plugin_entities as $plugin_entity) {
				$plugins[] = $plugin_entity->getID();
			}
		}

		$activated_plugins = [];

		// activate plugins that are not activated on install
		foreach ($plugins as $key => $id) {
			$plugin = elgg_get_plugin_from_id($id);
			if (!$plugin || $plugin->isActive()) {
				unset($plugins[$key]);
				continue;
			}
			if ($plugin->activate()) {
				$activated_plugins[] = $id;
			}
		}

		$suite = new TestSuite('Elgg Core Unit Tests');

		$test_cases = _elgg_services()->hooks->trigger('unit_test', 'system', null, []);
		foreach ($test_cases as $file) {
			$filter = $this->option('filter');
			if ($filter && !preg_match("/$filter/i", $file)) {
				continue;
			}

			if (substr($file, -4, 4) === '.php') {
				$suite->addFile($file);
			} else if (class_exists($file)) {
				if (is_subclass_of($file, \UnitTestCase::class)) {
					$suite->add($file);
				} else {
					elgg_log($file . ' is not a valid simpletest class');
				}
			}
		}

		$start_time = microtime(true);

		if (!$this->option('quiet')) {
			$reporter = new TextReporter();
		} else {
			$reporter = new \SimpleReporter();
		}

		$result = $suite->Run($reporter);

		// deactivate plugins that were activated for test suite
		foreach ($activated_plugins as $key => $id) {
			$plugin = elgg_get_plugin_from_id($id);
			$plugin->deactivate();
		}

		$formatter = new FormatterHelper();
		$message = $formatter->formatBlock(sprintf("Time: %.2f seconds, Memory: %.2fMb\n",
			microtime(true) - $start_time,
			memory_get_peak_usage() / 1048576.0 // in megabytes
		), 'info');

		$this->output->writeln($message);

		return $result ? 0 : 1;
	}
}
