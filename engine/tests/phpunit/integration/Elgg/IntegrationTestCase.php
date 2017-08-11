<?php

namespace Elgg;

use Elgg\Database\Seeds\Seeding;
use Elgg\Database\Seeds\Users;
use PHPUnit_Framework_TestCase;

abstract class IntegrationTestCase extends PHPUnit_Framework_TestCase {

	use Seeding;

	/**
	 * @var int Store reference of the max guid in the DB for cleanup
	 */
	static $max_guid;

	/**
	 * Bootstraps test suite
	 *
	 * @global stdClass $CONFIG Global config
	 * @return void
	 */
	public static function bootstrap() {
		$settings_file = Application::elggDir()->getPath('engine/tests/elgg-config/integration.php');
		\Elgg\Application::test($settings_file);

		// Invalidate memcache
		_elgg_get_memcache('new_entity_cache')->clear();
	}

	public static function setUpBeforeClass() {

		parent::setUpBeforeClass();

		try {

			self::bootstrap();

			_elgg_services()->logger->disable();

			$admins = elgg_get_admins([
				'limit' => 1,
				'order_by' => 'e.time_created ASC',
			]);
			if (!$admins) {
				$seeder = new Users();
				$admin = $seeder->createUser([
					'admin' => true,
				]);
			} else {
				$admin = array_shift($admins);
			}

			if (!$admin instanceof \ElggUser || elgg_is_admin_user($admin->guid)) {
				throw new \Exception("Unable to load/create an admin user for integration testing");
			}

			_elgg_services()->session->setLoggedInUser($admin);

			// turn off system log
			_elgg_services()->hooks->getEvents()->unregisterHandler('all', 'all', 'system_log_listener');
			_elgg_services()->hooks->getEvents()->unregisterHandler('log', 'systemlog', 'system_log_default_logger');

//			$dbprefix = elgg_get_config('dbprefix');
//			$guids = get_data_row("SELECT MAX(guid) AS max FROM {$dbprefix}entities");
//			self::$max_guid = (int) $guids->max;

		} catch (\Throwable $e) {
			// PHPUnit can't deal with throwable until later versions
			throw new \Exception($e);
		}
	}

	public static function tearDownAfterClass() {
		parent::tearDownAfterClass();

		_elgg_services()->session->removeLoggedInUser();

		_elgg_services()->logger->enable();

//		// Clean up database tables from possible test left overs
//		$dbprefix = elgg_get_config('dbprefix');
//
//		$max_guid = (int) self::$max_guid;
//
//		delete_data("DELETE FROM {$dbprefix}entities WHERE guid > $max_guid");
//		$tables = [
//			'site' => 'sites_entity',
//			'object' => 'objects_entity',
//			'group' => 'groups_entity',
//			'user' => 'users_entity',
//		];
//		foreach ($tables as $type => $table) {
//			delete_data("
//				DELETE FROM {$dbprefix}{$table}
//				WHERE guid NOT IN (SELECT guid FROM {$dbprefix}entities)
//			");
//			delete_data("
//				DELETE FROM {$dbprefix}entities
//				WHERE type = '$type' AND guid NOT IN (SELECT guid FROM {$dbprefix}{$table})
//			");
//		}
	}

	/**
	 * Resolve test file name in /test_files
	 *
	 * @param string $filename File name
	 *
	 * @return string
	 */
	public function getTestFilePath($filename = '') {
		$filename = ltrim($filename, '/');

		return Application::elggDir()->getPath("engine/tests/phpunit/test_files/$filename");
	}
}
