<?php
/**
 * Elgg Test Skeleton
 *
 * Plugin authors: copy this file to your plugin's test directory. Register an Elgg
 * plugin hook and function similar to:
 *
 * elgg_register_plugin_hook_handler('unit_test', 'system', 'my_new_unit_test');
 *
 * function my_new_unit_test($hook, $type, $value, $params) {
 *   $value[] = "path/to/my/unit_test.php";
 *   return $value;
 * }
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreSkeletonTest extends \Elgg\LegacyIntegrationTestCase {

	/**
	 * Called before each test method.
	 */
	public function setUp() {

		parent::setUp();

		try {
			_elgg_services()->db->connect();
		} catch (\DatabaseException $ex) {
			$this->markTestSkipped("This test can only run on a full Elgg installation");
		}
	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		parent::tearDown();
	}
}
