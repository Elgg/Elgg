<?php

namespace Elgg\Plugin;

use Elgg\IntegrationTestCase;
use Elgg\Exceptions\PluginException;

class ElggPluginDependenciesIntegrationTest extends IntegrationTestCase {

	/**
	 * {@inheritDoc}
	 */
	public function up() {
		
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {
		
	}
	
	public function testMeetsDependencies() {
		$plugin = \ElggPlugin::fromId('test_plugin', $this->normalizeTestFilePath('mod/'));
		
		$this->assertTrue($plugin->meetsDependencies());
	}
	
	public function testDoesntMeetDependencies() {
		$plugin = \ElggPlugin::fromId('dependent_plugin', $this->normalizeTestFilePath('mod/'));
		
		$this->assertFalse($plugin->meetsDependencies());
	}
	
	public function testAssertDependencies() {
		$plugin = \ElggPlugin::fromId('test_plugin', $this->normalizeTestFilePath('mod/'));
		
		$this->assertEmpty($plugin->assertDependencies());
	}
	
	public function testDoesntAssertDependencies() {
		$plugin = \ElggPlugin::fromId('dependent_plugin', $this->normalizeTestFilePath('mod/'));
		
		$this->expectException(PluginException::class);
		$plugin->assertDependencies();
	}
}
