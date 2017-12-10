<?php

/**
 * @group Plugins
 * @group ElggPlugin
 */
class ElggPluginUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	/**
	 * @expectedException PluginException
	 * @expectedExceptionMessage Plugin ID must be set
	 */
	public function testConstructorThrowsWithEmptyId() {
		ElggPlugin::fromId('');
	}

	public function testConstructsFromDatabaseRow() {

		$plugin = $this->createObject([
			'subtype' => 'plugin',
			'title' => 'test_plugin',
		]);

		$row = new \stdClass();
		$row->guid = $plugin->guid;
		$row->owner_guid = 1;
		$row->container_guid = 1;
		$row->access_id = ACCESS_PUBLIC;
		$row->enabled = 'yes';
		$row->time_created = $plugin->time_created;
		$row->time_updated = null;

		$constructed = new ElggPlugin($row);

		$this->assertEquals($row->guid, $constructed->guid);
	}

	public function testAttributesAreInitialized() {

		$plugin = ElggPlugin::fromId('test_plugin');

		$this->assertEquals('plugin', $plugin->getSubtype());
		$this->assertEquals('test_plugin', $plugin->getID());
		$this->assertEquals(\Elgg\Project\Paths::sanitize(elgg_get_plugins_path() . 'test_plugin'), $plugin->getPath());

	}

	public function testCanConstructWithCustomPath() {
		$plugin = ElggPlugin::fromId('test_plugin', $this->normalizeTestFilePath('mod/'));

		$this->assertEquals('plugin', $plugin->getSubtype());
		$this->assertEquals('test_plugin', $plugin->getID());
		$this->assertEquals($this->normalizeTestFilePath('mod/test_plugin/'), $plugin->getPath());

	}

	public function testCanLoadStaticConfig() {
		$plugin = ElggPlugin::fromId('test_plugin', $this->normalizeTestFilePath('mod/'));

		$config = [
			'entities' => [
				[
					'type' => 'object',
					'subtype' => 'test_plugin',
					'class' => 'TestPluginObject',
					'searchable' => true,
				],
			],
			'actions' => [
				'test_plugin/save' => [],
				'test_plugin/delete' => [],
			],
			'widgets' => [
				'test_plugin' => [
					'description' => elgg_echo('test_plugin:widget:description'),
					'context' => ['profile', 'dashboard'],
				],
			],
		];

		$this->assertEquals($config['entities'], $plugin->getStaticConfig('entities'));
		$this->assertEquals($config['actions'], $plugin->getStaticConfig('actions'));
		$this->assertEquals($config['widgets'], $plugin->getStaticConfig('widgets'));
	}

	public function testCanGetTextFiles() {
		$plugin = ElggPlugin::fromId('test_plugin', $this->normalizeTestFilePath('mod/'));

		$files = $plugin->getAvailableTextFiles();

		$this->assertEquals([
			'CHANGES.txt' => $this->normalizeTestFilePath('mod/test_plugin/CHANGES.txt'),
			'README' => $this->normalizeTestFilePath('mod/test_plugin/README'),
		], $files);
	}

	public function testCanReadManifest() {

		$plugin = ElggPlugin::fromId('test_plugin', $this->normalizeTestFilePath('mod/'));

		$manifest = $plugin->getManifest();
		$this->assertInstanceOf(ElggPluginManifest::class, $manifest);

		$this->assertEquals('Test Plugin', $plugin->getDisplayName());
	}

}