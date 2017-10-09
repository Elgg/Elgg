<?php

namespace Elgg\Config;

use Elgg\Mocks\Database;
use Elgg\UnitTestCase;

/**
 * @group Config
 * @group UnitTests
 */
class SettingsMigratorUnitTest extends UnitTestCase {

	/**
	 * @var Database
	 */
	protected $db;

	/**
	 * @var string
	 */
	protected $settings_path;

	public function up() {
		$this->db = _elgg_services()->db;

		$settings_path = $this->normalizeTestFilePath('test.settings.php');

		$lines = [
			"<?php",
			"",
			"global \$CONFIG;",
			"if (!isset(\$CONFIG)) {",
			"	\$CONFIG = new \stdClass;",
			"}"
		];

		file_put_contents($settings_path, implode(PHP_EOL, $lines), FILE_APPEND | LOCK_EX);

		$this->settings_path = $settings_path;
	}

	public function down() {
		unlink($this->settings_path);
	}

	public function normalizePath($path) {
		return str_replace('\\', '/', $path);
	}

	public function testDatarootSettingMigration() {
		if (defined('HHVM_VERSION')) {
			$this->markTestSkipped();
		}

		$config = _elgg_config()->getValues();

		$this->db->addQuerySpec([
			'sql' => "
				SELECT value FROM {$this->db->prefix}datalists
			  	WHERE name = 'dataroot'
			",
			'results' => [
				(object) [
					'value' => $this->normalizePath($config['dataroot']),
				],
			],
		]);

		$migrator = new DatarootSettingMigrator($this->db, $this->settings_path);
		$this->assertEquals($this->normalizePath($config['dataroot']), $migrator->migrate());

		// Resetting global because we need to make sure it's populated from the settings file
		global $CONFIG;
		$CONFIG = new \stdClass();

		include $this->settings_path;

		$this->assertEquals($this->normalizePath($config['dataroot']), $CONFIG->dataroot);
	}

	public function testWwwrootSettingMigration() {
		if (defined('HHVM_VERSION')) {
			$this->markTestSkipped();
		}

		$config = _elgg_config()->getValues();

		$this->db->addQuerySpec([
			'sql' => "
				SHOW TABLES LIKE '{$this->db->prefix}sites_entity'
			",
			'results' => [],
		]);

		$this->db->addQuerySpec([
			'sql' => "
				SELECT value FROM {$this->db->prefix}metadata
				WHERE name = 'url' AND
				entity_guid = 1
			",
			'results' => [
				(object) [
					'value' => $config['wwwroot'],
				],
			],
		]);

		$migrator = new WwwrootSettingMigrator($this->db, $this->settings_path);
		$this->assertEquals($config['wwwroot'], $migrator->migrate());

		// Resetting global because we need to make sure it's populated from the settings file
		global $CONFIG;
		$CONFIG = new \stdClass();

		include $this->settings_path;

		$this->assertEquals($config['wwwroot'], $CONFIG->wwwroot);
	}

}