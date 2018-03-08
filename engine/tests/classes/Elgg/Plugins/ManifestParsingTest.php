<?php

namespace Elgg\Plugins;

use Elgg\UnitTestCase;

class ManifestParsingTest extends UnitTestCase {

	use PluginTesting;

	/**
	 * @var \ElggPluginManifest
	 */
	private $manifest;

	public function up() {
		$id = $this->getPluginID();
		$plugin = \ElggPlugin::fromId($id);
		$this->manifest = $plugin->getManifest();
	}

	public function down() {

	}

	public function testHasName() {
		$this->assertNotEmpty($this->manifest->getName());
	}

	public function testHasDescription() {
		$this->assertNotEmpty($this->manifest->getDescription());
	}

	public function testHasId() {
		$this->assertNotEmpty($this->manifest->getID());
	}

	public function testHasAuthor() {
		$this->assertNotEmpty($this->manifest->getAuthor());
	}

	public function testHasLicense() {
		$this->assertNotEmpty($this->manifest->getLicense());
	}

	public function testHasCategories() {
		$this->assertNotEmpty($this->manifest->getCategories());
	}

}