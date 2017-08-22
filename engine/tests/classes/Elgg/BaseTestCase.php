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

	static $_instance;

	public function __construct($name = null, array $data = [], $dataName = '') {
		parent::__construct($name, $data, $dataName);

		self::$_instance = $this;
	}

	public function __destruct() {
		self::$_instance = null;
	}

	/**
	 * Build a new testing application
	 * @return Application|false
	 */
	public static function createApplication() {
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function setUp() {

		Application::setInstance(null);

		$app = static::createApplication();
		if (!$app) {
			$this->markTestSkipped();
		}

		$dt = new \DateTime();

		$app->_services->entityTable->setCurrentTime($dt);
		$app->_services->metadataTable->setCurrentTime($dt);
		$app->_services->relationshipsTable->setCurrentTime($dt);
		$app->_services->annotations->setCurrentTime($dt);
		$app->_services->usersTable->setCurrentTime($dt);

		// Invalidate memcache
		_elgg_get_memcache('new_entity_cache')->clear();

		$app->_services->session->removeLoggedInUser();
		$app->_services->session->setIgnoreAccess(false);
		access_show_hidden_entities(false);

		// Make sure the application has been bootstrapped correctly
		$this->assertInstanceOf(Application::class, elgg());
		$this->assertInstanceOf(ServiceProvider::class, $app->_services);
		$this->assertInstanceOf(Config::class, $app->_services->config);
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