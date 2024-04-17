<?php

namespace Elgg\Plugin;

use Elgg\UnitTestCase;
use Elgg\Exceptions\Plugin\ComposerException;
use Eloquent\Composer\Configuration\Element\Configuration;
use Elgg\Exceptions\Plugin\IdMismatchException;

class ComposerUnitTest extends UnitTestCase {

	/**
	 * @var \ElggPlugin
	 */
	protected $plugin;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->plugin = \ElggPlugin::fromId('test_plugin', $this->normalizeTestFilePath('mod/'));
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {
		if ($this->plugin instanceof \ElggPlugin) {
			$this->plugin->delete();
			unset($this->plugin);
		}
	}
	
	public function testConstructor() {
		$composer = $this->getComposer();
		
		$this->assertInstanceOf(Composer::class, $composer);
	}
	
	public function testConstructorWithInvalidPlugin() {
		$plugin = \ElggPlugin::fromId('invalid_plugin', $this->normalizeTestFilePath('mod/'));
		$this->assertInstanceOf(\ElggPlugin::class, $plugin);
		
		$this->expectException(ComposerException::class);
		$this->getComposer($plugin);
	}
	
	public function testGetConfiguration() {
		$composer = $this->getComposer();
		
		$config = $composer->getConfiguration();
		$this->assertInstanceOf(Configuration::class, $config);
	}
	
	public function testAssertPluginID() {
		$composer = $this->getComposer();
		$composer->assertPluginId();
		
		$plugin = $this->plugin;
		$plugin->title = 'invalid_plugin_id';
		
		$composer = $this->getComposer($plugin);
		
		$this->expectException(IdMismatchException::class);
		$composer->assertPluginId();
	}
	
	public function testGetLicense() {
		$composer = $this->getComposer();
		
		$this->assertEquals('GPL-2.0-only', $composer->getLicense());
	}
	
	public function testGetCategories() {
		$composer = $this->getComposer();
		
		$result = $composer->getCategories();
		$this->assertIsArray($result);
		$this->assertEquals([
			'content' => 'Content',
			'widget' => 'Widgets', // translated
			'elgg' => 'Elgg', // vendor
		], $result);
		$this->assertArrayNotHasKey('plugin', $result);
	}
	
	public function testGetConflicts() {
		$composer = $this->getComposer();
		
		$result = $composer->getConflicts();
		
		$this->assertIsArray($result);
		$this->assertEquals([
			'elgg' => '<1.9',
		], $composer->getConflicts());
	}
	
	/**
	 * @dataProvider validVersionProvider
	 */
	public function testCheckConstraintsValid($version_input, $version_constraint) {
		$composer = $this->getComposer();
		
		$this->assertTrue($composer->checkConstraints($version_input, $version_constraint));
	}
	
	public static function validVersionProvider(): array {
		return [
			['1.0.0', '*'],
			['1.2.0', '^1.0'],
			['2.0.0', '>1.0'],
		];
	}
	
	/**
	 * @dataProvider invalidVersionProvider
	 */
	public function testCheckConstraintsInvalid($version_input, $version_constraint) {
		$composer = $this->getComposer();
		
		$this->assertFalse($composer->checkConstraints($version_input, $version_constraint));
	}
	
	public static function invalidVersionProvider(): array {
		return [
			['1.2.0', '1.0'],
			['2.0.0', '<2.0'],
			['2.0.0', '<2.0'],
			// next is an invalid version string which should throw an exception which is caught
			['1.2.3!invalid', '*'],
			// next is a bug in Composer\Semver https://github.com/composer/semver/issues/157
			// once fixed this test should be moved to the valid tests to prevent regression
			['8.3.3-1+0~20240216.17+debian11~1.gbp87e37b', '*'],
		];
	}
	
	/**
	 * Get the composer reader
	 *
	 * @param \ElggPlugin $plugin plugin to read
	 *
	 * @return \Elgg\Plugin\Composer
	 */
	protected function getComposer(\ElggPlugin $plugin = null) {
		if (!$plugin instanceof \ElggPlugin) {
			$plugin = $this->plugin;
		}
		
		return new Composer($plugin);
	}
}
