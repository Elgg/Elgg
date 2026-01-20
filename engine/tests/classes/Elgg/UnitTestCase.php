<?php

namespace Elgg;

use Elgg\Mocks\Di\InternalContainer;
use Psr\Log\LogLevel;

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

		// persistentLogin service needs this set to instantiate without calling DB
		$config->getCookieConfig();
		$config->system_cache_enabled = elgg_extract('system_cache_enabled', $params, true);
		$config->plugins_path = elgg_extract('plugins_path', $params, $config->plugins_path);
		
		$sp = InternalContainer::factory(['config' => $config]);

		$app = Application::factory(array_merge([
			'internal_services' => $sp,
			'handle_exceptions' => false,
			'handle_shutdown' => false,
			'set_start_time' => false,
		], $params));

		$app->setGlobalConfig($app);

		Application::setInstance($app);
		
		// need to create site entity after services are available
		$app->internal_services->config->site = new \ElggSite((object) [
			'guid' => 1,
		]);
		
		if (in_array('--verbose', $_SERVER['argv'])) {
			$app->internal_services->logger->setLevel(LogLevel::DEBUG);
		} else {
			$app->internal_services->logger->setLevel(LogLevel::ERROR);
		}

		// Invalidate caches
		$app->internal_services->serverCache->clear();
		$app->internal_services->metadataCache->clear();
		$app->internal_services->accessCache->clear();

		// turn off system log
		$app->internal_services->events->unregisterHandler('all', 'all', 'Elgg\SystemLog\Logger::listen');
		$app->internal_services->events->unregisterHandler('log', 'systemlog', 'Elgg\SystemLog\Logger::log');

		return $app;
	}

	/**
	 * {@inheritdoc}
	 */
	final protected function setUp(): void {
		parent::setUp();

		$conf = \Elgg\Project\Paths::elgg() . 'engine/entities.php';
		$spec = \Elgg\Includer::includeFile($conf);

		foreach ($spec as $entity) {
			if (!isset($entity['type'], $entity['subtype'], $entity['class'])) {
				continue;
			}

			elgg_set_entity_class($entity['type'], $entity['subtype'], $entity['class']);
		}
		
		_elgg_services()->boot->boot(_elgg_services());

		$this->up();
	}

	/**
	 * {@inheritdoc}
	 */
	final protected function tearDown(): void {
		$this->down();

		$app = Application::getInstance();
		if ($app && $app->internal_services instanceof InternalContainer) {
			$app->internal_services->db->clearQuerySpecs();
		}

		parent::tearDown();
	}

	/**
	 * {@inheritdoc}
	 */
	public function createUser(array $properties = []): \ElggUser {
		$unique_id = uniqid('user');

		$defaults = [
			'name' => "John Doe {$unique_id}",
			'username' => "john_doe_{$unique_id}",
			'email' => "john_doe_{$unique_id}@example.com",
		];

		$properties = array_merge($defaults, $properties);

		$subtype = elgg_extract('subtype', $properties, 'foo_user');

		return _elgg_services()->entityTable->setup(null, 'user', $subtype, $properties);
	}

	/**
	 * {@inheritdoc}
	 */
	public function createObject(array $properties = []): \ElggObject {
		$subtype = elgg_extract('subtype', $properties, 'foo_object');

		return _elgg_services()->entityTable->setup(null, 'object', $subtype, $properties);
	}

	/**
	 * {@inheritdoc}
	 */
	public function createGroup(array $properties = []): \ElggGroup {
		$subtype = elgg_extract('subtype', $properties, 'foo_group');

		return _elgg_services()->entityTable->setup(null, 'group', $subtype, $properties);
	}

	/**
	 * {@inheritdoc}
	 */
	public function createSite(array $properties = []): \ElggSite {
		$subtype = elgg_extract('subtype', $properties, 'foo_site');

		return _elgg_services()->entityTable->setup(null, 'site', $subtype, $properties);
	}

}
