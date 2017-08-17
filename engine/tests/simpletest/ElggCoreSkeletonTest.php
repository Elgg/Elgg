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
 * @package    Elgg
 * @subpackage Test
 */
class ElggCoreSkeletonTest extends \ElggCoreUnitTest {

	public function up() {

	}

	public function down() {

	}

	public function testPass() {
		$this->asserTrue(true);
	}
}
