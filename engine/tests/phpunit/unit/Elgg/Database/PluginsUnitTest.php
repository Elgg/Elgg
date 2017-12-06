<?php

namespace Elgg\Database;

/**
 * @group UnitTests
 */
class PluginsUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testAfterPluginLoadActiveCheckIsFree() {
		$this->markTestIncomplete();
	}

	public function testPluginActivateAltersIsActive() {
		$this->markTestIncomplete();
	}

	public function testPluginDeactivateAltersIsActive() {
		$this->markTestIncomplete();
	}

	/**
	 * Check that plugins service is able to handle plugin user settings
	 */
	public function testSetGetAndUnsetUserSetting() {
		// TODO Use these once the class becomes testable:
		/*
		  $plugins = new \Elgg\Database\Plugins();
		  $user_guid = 123;

		  $plugins->setUserSetting('foo', 'bar', $user_guid, 'plugin_id');
		  $this->assertSame('bar', $plugins->getUserSetting('foo', $user_guid, 'plugin_id'));

		  $plugins->unsetUserSetting('foo', $user_guid, 'plugin_id');
		  $this->assertSame(null, $plugins->getUserSetting('foo', $user_guid, 'plugin_id'));
		 */

		$this->markTestIncomplete();
	}
}
