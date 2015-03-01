<?php
namespace Elgg\Database;

class PluginsTest extends \PHPUnit_Framework_TestCase {

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
