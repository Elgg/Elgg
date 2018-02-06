<?php

namespace Elgg;

use Elgg\Mocks\Di\MockServiceProvider;
use Symfony\Component\Console\Output\ConsoleOutput;

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
		$sp->config->system_cache_enabled = true;
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

		if (in_array('--verbose', $_SERVER['argv'])) {
			Logger::$verbosity = ConsoleOutput::VERBOSITY_VERY_VERBOSE;
		} else {
			Logger::$verbosity = ConsoleOutput::VERBOSITY_NORMAL;
		}

		// Invalidate caches
		$app->_services->dataCache->clear();
		$app->_services->sessionCache->clear();
		$app->_services->dic_cache->flushAll();

		// turn off system log
		$app->_services->hooks->getEvents()->unregisterHandler('all', 'all', 'system_log_listener');
		$app->_services->hooks->getEvents()->unregisterHandler('log', 'systemlog', 'system_log_default_logger');

		return $app;
	}

	/**
	 * {@inheritdoc}
	 */
	final protected function setUp() {
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
	final protected function tearDown() {
		$this->down();

		_elgg_services()->db->clearQuerySpecs();

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
			'banned' => 'no',
			'admin' => 'no',
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

