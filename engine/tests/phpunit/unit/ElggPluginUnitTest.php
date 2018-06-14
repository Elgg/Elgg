<?php

/**
 * @group Plugins
 * @group ElggPlugin
 */
class ElggPluginUnitTest extends \Elgg\UnitTestCase {

	public function up() {
		_elgg_services()->boot->invalidateCache();
	}

	public function down() {

	}

	/**
	 * @expectedException InvalidArgumentException
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

		$plugin->delete();
	}

	public function testAttributesAreInitialized() {

		$plugin = ElggPlugin::fromId('test_plugin');

		$this->assertEquals('plugin', $plugin->getSubtype());
		$this->assertEquals('test_plugin', $plugin->getID());
		$this->assertEquals(\Elgg\Project\Paths::sanitize(elgg_get_plugins_path() . 'test_plugin'), $plugin->getPath());

		$plugin->delete();
	}

	public function testCanConstructWithCustomPath() {
		$plugin = ElggPlugin::fromId('test_plugin', $this->normalizeTestFilePath('mod/'));

		$this->assertEquals('plugin', $plugin->getSubtype());
		$this->assertEquals('test_plugin', $plugin->getID());
		$this->assertEquals($this->normalizeTestFilePath('mod/test_plugin/'), $plugin->getPath());

		$plugin->delete();
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
			'routes' => [
				'plugin:foo' => [
					'path' => '/plugin/{foo?}',
					'resource' => 'plugin/foo',
				],
			]
		];

		$this->assertEquals($config['entities'], $plugin->getStaticConfig('entities'));
		$this->assertEquals($config['actions'], $plugin->getStaticConfig('actions'));
		$this->assertEquals($config['widgets'], $plugin->getStaticConfig('widgets'));
		$this->assertEquals($config['routes'], $plugin->getStaticConfig('routes'));

		$plugin->delete();
	}

	public function testCanGetTextFiles() {
		$plugin = ElggPlugin::fromId('test_plugin', $this->normalizeTestFilePath('mod/'));

		$files = $plugin->getAvailableTextFiles();

		$this->assertEquals([
			'CHANGES.txt' => $this->normalizeTestFilePath('mod/test_plugin/CHANGES.txt'),
			'README' => $this->normalizeTestFilePath('mod/test_plugin/README'),
		], $files);

		$plugin->delete();
	}

	public function testCanReadManifest() {

		$plugin = ElggPlugin::fromId('test_plugin', $this->normalizeTestFilePath('mod/'));

		$manifest = $plugin->getManifest();
		$this->assertInstanceOf(ElggPluginManifest::class, $manifest);

		$this->assertEquals('Test Plugin', $plugin->getDisplayName());

		$plugin->delete();
	}

	public function testUsesBootstrapOnActivate() {

		$plugin = ElggPlugin::fromId('bootstrap_plugin', $this->normalizeTestFilePath('mod/'));

		$plugin->activate();

		$methods = [
			'load',
			'boot',
			'init',
			'activate',
		];

		foreach ($methods as $method) {
			$prop = BootstrapPluginTestBootstrap::class . '::' . $method . '_calls';
			$this->assertEquals(1, $plugin->$prop, "Method $method was called {$plugin->$prop} instead of expected 1 times");
		}

		// elgg-plugin.php should only be included once
		global $BOOTSTRAP_PLUGIN_TEST;
		$this->assertEquals(1, $BOOTSTRAP_PLUGIN_TEST);
	}

	public function testUsesBootstrapOnDeactivate() {

		$plugin = ElggPlugin::fromId('bootstrap_plugin', $this->normalizeTestFilePath('mod/'));

		$plugin->activate();
		$plugin->deactivate();

		$methods = [
			'activate',
			'deactivate',
		];

		foreach ($methods as $method) {
			$prop = BootstrapPluginTestBootstrap::class . '::' . $method . '_calls';
			$this->assertEquals(1, $plugin->$prop, "Method $method was called {$plugin->$prop} instead of expected 1 times");
		}

		// elgg-plugin.php should only be included once
		global $BOOTSTRAP_PLUGIN_TEST;
		$this->assertEquals(1, $BOOTSTRAP_PLUGIN_TEST);
	}

	public function testUsesBootstrapOnBoot() {

		$app = $this->createApplication();

		elgg_set_entity_class('object', 'plugin', ElggPlugin::class);

		$plugin = ElggPlugin::fromId('bootstrap_plugin', $this->normalizeTestFilePath('mod/'));

		$app->_services->config->boot_cache_ttl = 0;
		$app->_services->plugins->addTestingPlugin($plugin);

		$app->bootCore();

		$methods = [
			'load',
			'boot',
			'init',
			'ready',
		];

		foreach ($methods as $method) {
			$prop = BootstrapPluginTestBootstrap::class . '::' . $method . '_calls';
			$this->assertEquals(1, $plugin->$prop, "Method $method was called {$plugin->$prop} instead of expected 1 times");
		}

		// elgg-plugin.php should only be included once
		global $BOOTSTRAP_PLUGIN_TEST;
		$this->assertEquals(1, $BOOTSTRAP_PLUGIN_TEST);
	}

	/**
	 * @group UpgradeService
	 */
	public function testUsesBootstrapOnUpgrade() {

		$app = $this->createApplication();

		// We don't need to run actual upgrades for this test
		$locator = $this->createMock(\Elgg\Upgrade\Locator::class);
		$locator->method('locate')
			->willReturn([]);

		$app->_services->setValue('upgradeLocator', $locator);

		$app->bootCore();

		$plugin = ElggPlugin::fromId('bootstrap_plugin', $this->normalizeTestFilePath('mod/'));
		$plugin->activate();

		$prefix = _elgg_config()->dbprefix;

		_elgg_services()->db->addQuerySpec([
			'sql' => "SHOW TABLES LIKE '{$prefix}upgrade_lock'",
			'results' => null,
		]);

		_elgg_services()->db->addQuerySpec([
			'sql' => "CREATE TABLE {$prefix}upgrade_lock (id INT)",
		]);

		_elgg_services()->db->addQuerySpec([
			'sql' => "DROP TABLE {$prefix}upgrade_lock",
		]);

		$config = _elgg_services()->dbConfig->getConnectionConfig(\Elgg\Database\DbConfig::READ_WRITE);

		_elgg_services()->db->addQuerySpec([
			'sql' => "SHOW TABLE STATUS FROM `{$config['database']}`",
		]);

		_elgg_services()->plugins->addTestingPlugin($plugin);

		$assertions = 0;
		$assert = function() use ($plugin, &$assertions) {
			$methods = [
				'upgrade',
			];

			foreach ($methods as $method) {
				$prop = BootstrapPluginTestBootstrap::class . '::' . $method . '_calls';
				$this->assertEquals(1, $plugin->$prop, "Method $method was called {$plugin->$prop} instead of expected 1 times");
			}

			// elgg-plugin.php should only be included once
			global $BOOTSTRAP_PLUGIN_TEST;
			$this->assertEquals(1, $BOOTSTRAP_PLUGIN_TEST);

			$assertions++;
		};

		$fail = function($error) {
			if ($error instanceof Throwable) {
				$error = $error->getMessage();
			} else if (is_array($error)) {
				$error = array_shift($error);
			}

			$this->fail($error);
		};

		$upgrade = _elgg_services()->upgrades->run();
		$upgrade->done($assert, $fail);

		$this->assertEquals(1, $assertions);
	}

	public function testUsesBootstrapOnShutdown() {
		/* @todo Test that bootstrap handlers are called during the shutdown event */

		$this->markTestIncomplete();
	}
}