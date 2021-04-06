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
		
		$this->assertEmpty($composer->assertPluginId());
		
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
