<?php

namespace Elgg;

use Elgg\Mocks\Di\MockServiceProvider;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Unit test abstraction class
 *
 * Extend this class to run PHPUnit tests without a database connection
 */
abstract class UnitTestCase extends BaseTestCase {

	/**
	 * {@inheritdoc}
	 */
	public static function createApplication(array $params = []) {

		Application::setInstance(null);

		$config = self::getTestingConfig();
		$sp = new MockServiceProvider($config);

		// persistentLogin service needs this set to instantiate without calling DB
		$sp->config->getCookieConfig();
		$sp->config->boot_complete = false;
		$sp->config->system_cache_enabled = elgg_extract('system_cache_enabled', $params, true);
		$sp->config->plugins_path = elgg_extract('plugins_path', $params);
		$sp->config->site = new \ElggSite((object) [
			'guid' => 1,
		]);

		$app = Application::factory(array_merge([
			'service_provider' => $sp,
			'handle_exceptions' => false,
			'handle_shutdown' => false,
			'set_start_time' => false,
		], $params));

		Application::setInstance($app);

		$cli_output = new NullOutput();
		$cli_output->setVerbosity(OutputInterface::VERBOSITY_VERBOSE);
		$app->_services->setValue('cli_output', $cli_output);

		if (in_array('--verbose', $_SERVER['argv'])) {
			$app->_services->logger->setLevel(LogLevel::DEBUG);
		} else {
			$app->_services->logger->setLevel(LogLevel::ERROR);
		}

		// Invalidate caches
		$app->_services->dataCache->clear();
		$app->_services->sessionCache->clear();

		// turn off system log
		$app->_services->events->unregisterHandler('all', 'all', 'Elgg\SystemLog\Logger::listen');
		$app->_services->events->unregisterHandler('log', 'systemlog', 'Elgg\SystemLog\Logger::log');

		return $app;
	}

	/**
	 * {@inheritdoc}
	 */
	final protected function setUp(): void {
		parent::setUp();

		elgg_set_entity_class('user', 'user', \ElggUser::class);
		elgg_set_entity_class('group', 'group', \ElggGroup::class);
		elgg_set_entity_class('site', 'site', \ElggSite::class);
		elgg_set_entity_class('object', 'plugin', \ElggPlugin::class);
		elgg_set_entity_class('object', 'file', \ElggFile::class);
		elgg_set_entity_class('object', 'widget', \ElggWidget::class);
		elgg_set_entity_class('object', 'comment', \ElggComment::class);
		elgg_set_entity_class('object', 'elgg_upgrade', \ElggUpgrade::class);

		_elgg_services()->boot->boot(_elgg_services());

		self::$_instance = $this;

		$this->up();
	}

	/**
	 * {@inheritdoc}
	 */
	final protected function tearDown(): void {
		$this->down();

		$app = Application::getInstance();
		if ($app && $app->_services instanceof MockServiceProvider) {
			$app->_services->db->clearQuerySpecs();
		}

		parent::tearDown();
	}

	/**
	 * {@inheritdoc}
	 */
	public function createUser(array $attributes = [], array $metadata = []) {
		$unique_id = uniqid('user');

		$defaults = [
			'name' => "John Doe {$unique_id}",
			'username' => "john_doe_{$unique_id}",
			'email' => "john_doe_{$unique_id}@example.com",
		];

		$attributes = array_merge($defaults, $metadata, $attributes);

		$subtype = isset($attributes['subtype']) ? $attributes['subtype'] : 'foo_user';

		return _elgg_services()->entityTable->setup(null, 'user', $subtype, $attributes);
	}

	/**
	 * {@inheritdoc}
	 */
	public function createObject(array $attributes = [], array $metadata = []) {
		$attributes = array_merge($metadata, $attributes);

		$subtype = isset($attributes['subtype']) ? $attributes['subtype'] : 'foo_object';

		return _elgg_services()->entityTable->setup(null, 'object', $subtype, $attributes);
	}

	/**
	 * {@inheritdoc}
	 */
	public function createGroup(array $attributes = [], array $metadata = []) {
		$attributes = array_merge($metadata, $attributes);

		$subtype = isset($attributes['subtype']) ? $attributes['subtype'] : 'foo_group';

		return _elgg_services()->entityTable->setup(null, 'group', $subtype, $attributes);
	}

	/**
	 * {@inheritdoc}
	 */
	public function createSite(array $attributes = [], array $metadata = []) {
		$attributes = array_merge($metadata, $attributes);

		$subtype = isset($attributes['subtype']) ? $attributes['subtype'] : 'foo_site';

		return _elgg_services()->entityTable->setup(null, 'site', $subtype, $attributes);
	}

}
