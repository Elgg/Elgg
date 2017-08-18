<?php
/**
 *
 */

namespace Elgg;

use Elgg\Database\Seeds\Seedable;
use Elgg\Di\ServiceProvider;
use PHPUnit_Framework_TestCase;

/**
 * Base test case abstraction
 */
abstract class BaseTestCase extends PHPUnit_Framework_TestCase implements Seedable, Testable {

	use Testing;

	/**
	 * Returns path to settings file to be used to boostrap the Application
	 * @return mixed
	 */
	abstract public static function getSettingsPath();

	/**
	 * Returns names of services that should be reset when BaseTestCase::reset() is called
	 * @return mixed
	 */
	abstract public static function getResettableServices();

	/**
	 * {@inheritdoc}
	 */
	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();
		try {
			static::bootstrap();
		} catch (\Throwable $t) {
			throw new \Exception($t->getMessage(), $t->getCode(), $t);
		}
	}

	/**
	 * Build a new testing application
	 * @return Application
	 */
	abstract public static function createApplication();

	/**
	 * Boostrap a new instance of a testing application
	 *
	 * @return Application
	 */
	public static function bootstrap() {

		$app = Application::$_instance;

		if (!self::isSamePath($app->_services->config->elgg_settings_file, static::getSettingsPath())) {
			$backup_values = [];

			$keys = [
				'testCase',
			];

			foreach ($keys as $key) {
				$backup_values[$key] = _elgg_services()->$key;
			}

			$app = static::createApplication();

			foreach ($backup_values as $key => $value) {
				_elgg_services()->setValue($key, $value);
			}

			return $app;
		} else {
			// Otherwise just reset it
			self::reset();
			return $app;
		}
	}

	/**
	 * Compare if two paths are equal
	 *
	 * @param string $path1 Path
	 * @param string $path2 Path
	 *
	 * @return bool
	 */
	private static function isSamePath($path1, $path2) {
		$normalize = function($path) {
			return str_replace('\\', '/', $path);
		};

		return $normalize($path1) == $normalize($path2);
	}

	/**
	 * Reset the application to original state without bootstrapping it all over again
	 * @return void
	 */
	public static function reset() {
		foreach (static::getResettableServices() as $service) {
			_elgg_services()->reset($service);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	protected function setUp() {

		_elgg_services()->logger->notice('Test started: ' . $this->getName());

		self::reset();

		$dt = new \DateTime();
		_elgg_services()->entityTable->setCurrentTime($dt);
		_elgg_services()->metadataTable->setCurrentTime($dt);
		_elgg_services()->relationshipsTable->setCurrentTime($dt);
		_elgg_services()->annotations->setCurrentTime($dt);
		_elgg_services()->usersTable->setCurrentTime($dt);

		// turn off system log
		_elgg_services()->hooks->getEvents()->unregisterHandler('all', 'all', 'system_log_listener');
		_elgg_services()->hooks->getEvents()->unregisterHandler('log', 'systemlog', 'system_log_default_logger');

		_elgg_services()->setValue('testCase', $this);

		// Invalidate memcache
		_elgg_get_memcache('new_entity_cache')->clear();

		_elgg_services()->session->removeLoggedInUser();
		_elgg_services()->session->setIgnoreAccess(false);
		access_show_hidden_entities(false);

		// Make sure the application has been bootstrapped correctly
		$this->assertInstanceOf(Application::class, elgg());
		$this->assertInstanceOf(ServiceProvider::class, _elgg_services());
		$this->assertInstanceOf(Config::class, _elgg_services()->config);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function tearDown() {

		// We do not want overflowing ignored access
		$this->assertFalse((bool) elgg_get_ignore_access());

		// We do not want overflowing show hidden status
		$this->assertFalse((bool) access_get_show_hidden_status());

		// Tests should run without a logged in user
		$this->assertFalse((bool) elgg_is_logged_in());

		// free up some memory
		$refl = new \ReflectionObject($this);
		foreach ($refl->getProperties() as $prop) {
			if (!$prop->isStatic() && 0 !== strpos($prop->getDeclaringClass()->getName(), 'PHPUnit_')) {
				$prop->setAccessible(true);
				$prop->setValue($this, null);
			}
		}

		_elgg_services()->logger->notice('Test ended: ' . $this->getName());
	}

	/**
	 * Called after setUp() method and can be used by test cases to setup their test logic
	 * @return mixed
	 */
	abstract function up();

	/**
	 * Called before tearDown() method and can be used by test cases to clear their test logic
	 * @return mixed
	 */
	abstract function down();

}